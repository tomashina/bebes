<?php
/**
 * Shared upload validation for legacy OpenCart controllers.
 *
 * This class intentionally fails closed when the server cannot determine the
 * real MIME type or when a destination cannot be proven to be inside its root.
 */
class BebesUploadGuard {
	const MAX_IMAGE_BYTES = 20971520;
	const MAX_IMAGE_PIXELS = 40000000;

	private static $blocked_extensions = array(
		'php', 'php2', 'php3', 'php4', 'php5', 'php7', 'php8', 'php9',
		'phtml', 'pht', 'phtm', 'phar', 'phps', 'shtml', 'cgi', 'pl', 'py', 'sh'
	);

	public static function canonicalDirectory($root, $relative = '', $create = false) {
		$root = realpath($root);

		if ($root === false || !is_string($relative)) {
			return false;
		}

		$relative = str_replace('\\', '/', html_entity_decode($relative, ENT_QUOTES, 'UTF-8'));

		if (strpos($relative, "\0") !== false || preg_match('/[\x00-\x1F\x7F]/', $relative)) {
			return false;
		}

		if ($relative !== '' && ($relative[0] === '/' || preg_match('#(^|/)\.\.?(/|$)#', $relative))) {
			return false;
		}

		$relative = trim($relative, '/');

		if ($relative === '') {
			return $root;
		}

		$segments = explode('/', $relative);

		if (strlen($relative) > 512 || count($segments) > 20) {
			return false;
		}

		foreach ($segments as $segment) {
			if ($segment === '' || strlen($segment) > 128) {
				return false;
			}
		}

		$candidate = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);

		if (!is_dir($candidate) && (!$create || !mkdir($candidate, 0755, true))) {
			return false;
		}

		$directory = realpath($candidate);

		if ($directory === false || !self::pathIsInside($directory, $root)) {
			return false;
		}

		return $directory;
	}

	public static function safeBasename($name, array $allowed_extensions = array()) {
		if (!is_string($name)) {
			return false;
		}

		$name = html_entity_decode($name, ENT_QUOTES, 'UTF-8');
		$normalized = str_replace('\\', '/', $name);
		$filename = basename($normalized);

		if ($filename === '' || $filename !== $normalized || $filename[0] === '.' || strlen($filename) > 255) {
			return false;
		}

		if (strpos($filename, "\0") !== false || preg_match('/[\x00-\x1F\x7F]/', $filename)) {
			return false;
		}

		$parts = array_map('strtolower', explode('.', $filename));
		$extension = count($parts) > 1 ? end($parts) : '';

		foreach ($parts as $part) {
			if (in_array($part, self::$blocked_extensions, true) || preg_match('/^php[0-9]*$/', $part)) {
				return false;
			}
		}

		if ($extension === '' || ($allowed_extensions && !in_array($extension, $allowed_extensions, true))) {
			return false;
		}

		return $filename;
	}

	public static function validateUploadedFile(array $file, array $allowed_extensions, array $allowed_mimes, array $extension_mimes, $require_raster, &$error, $max_bytes = null) {
		$error = '';

		if (!isset($file['error']) || (int)$file['error'] !== UPLOAD_ERR_OK) {
			$error = 'upload_error';
			return false;
		}

		if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
			$error = 'not_uploaded_file';
			return false;
		}

		$filename = self::safeBasename(isset($file['name']) ? $file['name'] : '', $allowed_extensions);

		if ($filename === false) {
			$error = 'invalid_filename_or_extension';
			return false;
		}

		$size = filesize($file['tmp_name']);

		if ($size === false || $size < 1 || ($max_bytes !== null && $size > $max_bytes)) {
			$error = 'invalid_file_size';
			return false;
		}

		$mime = self::detectMime($file['tmp_name']);

		if ($mime === false || !in_array($mime, $allowed_mimes, true)) {
			$error = 'invalid_mime';
			return false;
		}

		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

		if (isset($extension_mimes[$extension]) && !in_array($mime, $extension_mimes[$extension], true)) {
			$error = 'extension_mime_mismatch';
			return false;
		}

		if ($require_raster) {
			$image = @getimagesize($file['tmp_name']);

			if (!$image || empty($image[0]) || empty($image[1]) || empty($image['mime'])) {
				$error = 'invalid_image';
				return false;
			}

			if (!in_array(strtolower($image['mime']), $allowed_mimes, true) || ((int)$image[0] * (int)$image[1]) > self::MAX_IMAGE_PIXELS) {
				$error = 'invalid_image_dimensions_or_mime';
				return false;
			}
		}

		return array(
			'filename'  => $filename,
			'extension' => $extension,
			'mime'      => $mime,
			'tmp_name'  => $file['tmp_name']
		);
	}

	/**
	 * Durable preflight for the DB-backed Simple Store OCMOD. This runs from
	 * admin/startup/permission, so regenerated vulnerable controller code still
	 * cannot receive traversal paths, executable filenames or remote URLs.
	 */
	public static function validateSimpleStoreRequest(array $post, array $files, &$error) {
		$error = '';
		$catalog = isset($post['catalog']) && is_string($post['catalog']) ? str_replace('\\', '/', $post['catalog']) : '';
		$catalog = trim($catalog, '/');
		$relative = false;

		if ($catalog === 'catalog') {
			$relative = '';
		} elseif (strpos($catalog, 'catalog/') === 0) {
			$relative = substr($catalog, 8);
		}

		// Remote imports are disabled at preflight because the original OCMOD
		// follows redirects and can be restored by a modification cache refresh.
		if (!empty($post['urls'])) {
			$error = 'remote_import_disabled';
			return false;
		}

		if ($relative === false || self::canonicalDirectory(DIR_IMAGE . 'catalog', $relative, true) === false) {
			$error = 'invalid_image_directory';
			return false;
		}

		if (!empty($post['dels'])) {
			if (!is_array($post['dels']) || empty($post['dels']['value']) || !is_array($post['dels']['value'])) {
				$error = 'invalid_delete_request';
				return false;
			}

			foreach ($post['dels']['value'] as $relative_image) {
				if (self::existingFile(DIR_IMAGE, $relative_image, array('jpg', 'jpeg', 'png', 'gif')) === false) {
					$error = 'invalid_delete_path';
					return false;
				}
			}
		}

		if (count($files) > 20) {
			$error = 'too_many_files';
			return false;
		}

		$extensions = array('jpg', 'jpeg', 'png', 'gif');
		$mimes = array('image/jpeg', 'image/png', 'image/gif');
		$extension_mimes = array(
			'jpg'  => array('image/jpeg'),
			'jpeg' => array('image/jpeg'),
			'png'  => array('image/png'),
			'gif'  => array('image/gif')
		);

		foreach ($files as $file) {
			if (!is_array($file) || self::validateUploadedFile($file, $extensions, $mimes, $extension_mimes, true, $error, self::MAX_IMAGE_BYTES) === false) {
				return false;
			}
		}

		return true;
	}

	public static function moveValidatedUpload(array $validated, $directory, $stored_name, &$error) {
		$error = '';
		$directory = realpath($directory);
		$stored_name = self::safeBasename($stored_name);

		if ($directory === false || $stored_name === false) {
			$error = 'invalid_destination';
			return false;
		}

		$destination = $directory . DIRECTORY_SEPARATOR . $stored_name;

		if (is_link($destination) || !move_uploaded_file($validated['tmp_name'], $destination)) {
			$error = 'move_failed';
			return false;
		}

		@chmod($destination, 0644);

		return $destination;
	}

	public static function existingFile($root, $relative, array $allowed_extensions = array()) {
		$root = realpath($root);

		if ($root === false || !is_string($relative)) {
			return false;
		}

		$relative = str_replace('\\', '/', html_entity_decode($relative, ENT_QUOTES, 'UTF-8'));

		if ($relative === '' || $relative[0] === '/' || strpos($relative, "\0") !== false || preg_match('#(^|/)\.\.?(/|$)#', $relative)) {
			return false;
		}

		$filename = self::safeBasename(basename($relative), $allowed_extensions);

		if ($filename === false) {
			return false;
		}

		$path = realpath($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative));

		if ($path === false || !self::pathIsInside($path, $root) || !is_file($path) || is_link($path)) {
			return false;
		}

		return $path;
	}

	private static function detectMime($path) {
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = $finfo ? finfo_file($finfo, $path) : false;

			if ($finfo) {
				finfo_close($finfo);
			}

			if ($mime) {
				return strtolower(trim($mime));
			}
		}

		if (function_exists('mime_content_type')) {
			$mime = @mime_content_type($path);

			if ($mime) {
				return strtolower(trim($mime));
			}
		}

		return false;
	}

	private static function pathIsInside($path, $root) {
		$path = rtrim(str_replace('\\', '/', $path), '/');
		$root = rtrim(str_replace('\\', '/', $root), '/');

		return $path === $root || strpos($path . '/', $root . '/') === 0;
	}

}
