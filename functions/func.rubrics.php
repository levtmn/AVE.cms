<?php

	/**
	 * Функция отдает время когда менялась рубрика или ее поля
	 *
	 * @param int $rubric_id
	 * @param int $var
	 *
	 * @return mixed
	 */
	function get_rubrics_changes ($rubric_id = null, $var = '')
	{
		global $AVE_DB;

		$cache_file = BASE_DIR . '/tmp/cache/sql/rubrics/all/rubrics.cahnges';

		// Если включен DEV MODE, то отключаем кеширование запросов
		if (defined('DEV_MODE') and DEV_MODE)
			$cache_file = null;

		if (! is_dir(dirname($cache_file)))
			mkdir(dirname($cache_file), 0766, true);

		if (file_exists($cache_file))
		{
			$rubrics = unserialize(file_get_contents($cache_file));
		}
		else
			{
				$query = "
					SELECT
						Id,
						rubric_changed,
						rubric_changed_fields
					FROM
						" . PREFIX . "_rubrics
				";

				$sql = $AVE_DB->Query($query);

				$rubrics = [];

				while ($row = $sql->FetchAssocArray())
					$rubrics[$row['Id']] =  $row;

				if ($cache_file)
					file_put_contents($cache_file, serialize($rubrics));
			}

		if ($rubric_id > 0)
		{
			if (! empty($var))
				return $rubrics[$rubric_id][$var];
			else
				return $rubrics[$rubric_id];
		}
		else
			{
				return $rubrics;
			}
	}
?>