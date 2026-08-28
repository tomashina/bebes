<?php
class ModelExtensionExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

		if ($type !== 'payment') {
			return $query->rows;
		}

		// Store policy: checkout may expose only these three reviewed methods,
		// even if another extension is enabled directly in the database.
		$allowed = array('wspay', 'cod', 'kekspay');
		$extensions = array();

		foreach ($query->rows as $extension) {
			if (isset($extension['code']) && in_array($extension['code'], $allowed, true)) {
				$extensions[] = $extension;
			}
		}

		return $extensions;
	}
}
