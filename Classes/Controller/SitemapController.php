<?php

/**
 * The sitemap controller for the DynamicSitemap package
 */
class Tx_DynamicSitemap_Controller_SitemapController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * the main action
	 *
	 * @return string xml-string
	 */
	public function indexAction() {
		$sitemap = array();
		$pageChanges = array();
		$this->view->assign('baseUrl', $GLOBALS['TSFE']->tmpl->setup['config.']['baseURL']);
		$config = Tx_Extbase_Utility_TypoScript::convertExtbaseToClassicTS($this->settings);

		foreach ($config as $key => $value) {

			if (is_array($value)) {

				foreach ($value as $key => $value) {

					if (!is_int($key)) {
						$result = explode('|', rtrim(str_replace('</a>', '|', $GLOBALS['TSFE']->cObj->HMENU($value)), '|'));

						foreach ($result as $key => $value) {
							$result[$key] = explode('|', str_replace(array('<a href="', '"  >'), array('', '|'), $value));
							$pathParts = explode('/', $result[$key][0]);
							array_pop($pathParts);
							$realUrlResult = $GLOBALS['T3_VAR']['callUserFunction_classPool']['tx_realurl_advanced']->pagePathtoID($pathParts);

							if (!array_key_exists($realUrlResult[0], $pageChanges)) {
								$row = t3lib_BEfunc::getRecord('pages', $realUrlResult[0], 'SYS_LASTCHANGED,tx_dynamicsitemap_lastmod');
								$pageChanges[$realUrlResult[0]] = $row;
							}

							$result[$key]['lastmod'] = date('Y-m-d', $pageChanges[$realUrlResult[0]]['SYS_LASTCHANGED']);
							$result[$key]['changefreq'] = $this->getChangeFrequency($pageChanges[$realUrlResult[0]]['tx_dynamicsitemap_lastmod'], $pageChanges[$realUrlResult[0]]['SYS_LASTCHAGEND']);
						}

						$sitemap = array_merge($sitemap, $result);
					}

				}

			}

		}

		$this->view->assign('sitemap', $sitemap);
	}

	/**
	 * Returns the change-frequency according to sitemaps.org
	 *
	 * @param string
	 * @param int
	 * @return string
	 */
	protected function getChangeFrequency($lastMod, $lastChanged) {
		$timeValues = t3lib_div::intExplode(',', $pageInfo['tx_dynamicsitemap_lastmod']);

		foreach ($timeValues as $key => $value) {

			if ($value == 0) {
				unset($timeValues[$key]);
			}

		}

		$timeValues[] = $lastChanged;
		$timeValues[] = time();
		sort($timeValues, SORT_NUMERIC);
		$sum = 0;

		for ($i = count($timeValues) - 1; $i > 0; $i--) {
			$sum += ($timeValues[$i] - $timeValues[$i - 1]);
		}

		$average = ($sum / (count($timeValues) - 1));
		return ($average >= 180*24*60*60 ? 'yearly' :
				($average <= 24*60*60 ? 'daily' :
					($average <= 60*60 ? 'hourly' :
						($average <= 14*24*60*60 ? 'weekly' : 'monthly')
					)
				)
			);
	}

}

