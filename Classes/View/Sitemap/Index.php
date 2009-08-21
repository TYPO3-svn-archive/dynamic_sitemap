<?php

class Tx_DynamicSitemap_View_Sitemap_Index extends Tx_Extbase_MVC_View_AbstractView {

	public function initializeView() {
	}

	public function render() {
		$content = '';

		foreach ($this->viewData['sitemap'] as $value) {
			$content .= '<url>';
			$content .= '<loc>' . $this->viewData['baseUrl'] . $value[0] . '</loc>';
			$content .= '<lastmod>' . $value['lastmod'] . '</lastmod>';
			$content .= '<changefreq>' . $value['changefreq'] . '</changefreq>';
			$content .= '</url>';
		}

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . $content . '</urlset>');
		return $xml->asXML();
	}

}

