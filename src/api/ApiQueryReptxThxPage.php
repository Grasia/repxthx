<?php

class ApiQueryReptxThxPage extends ApiQueryBase {

    public function __construct($query, $moduleName) {
        parent::__construct($query, $moduleName, 'rxtp');
    }

    public function execute() {

        $params = $this->extractRequestParams();
        $page = null;
        if (isset($params['pageTitle']) && isset($params['pageId'])) {
            $this->dieUsage('Parameters userName and userId set at the same time', 'bothParamsSet');
        } else if (isset($params['pageTitle'])) {
            $page = self::getPageByTitle($params['pageTitle'], $params);
        } else if (isset($params['pageId'])) {
            $page = self::getPageById($params['pageId'], $params);
        } else {
            $this->dieUsage('pageTitle parameter is not set', 'noPageTitle');
        }

        
        $fitness = $page->getFitness();
//        $cred = $page->getCreditValue();
        $lastFitUpd = $page->getLastFitnessUpdateTimestamp();
//        $lastCredUpd = $page->getCredLastUpdatedTimestamp();

        $r = array(
            'fitness' => $fitness === null ? 0 : $fitness,
            'lastFitUpdateTimestamp' => $lastFitUpd === null ? 0 : $lastFitUpd,
        );

        $this->getResult()->addValue(null, $this->getModuleName(), $r);
    }

    public function getAllowedParams() {
        return array(
            'pageTitle' => array(
                ApiBase::PARAM_TYPE => 'string'
            ),
            'pageId' => array(
                ApiBase::PARAM_TYPE => 'integer'
            )
        );
    }

    private function getPageByTitle($name, $params) {
        $mediawikiTitle = Title::newFromText($name);

        if ($mediawikiTitle->exists()) {
            $reptxthxPage = ReptxThxPage::newFromId($mediawikiTitle->getArticleId());

            return $reptxthxPage;
        } else {
            $this->dieUsage('User with name ' . $params['pageTitle'] . ' does not exists', 'invalidUserName');
        }
    }

    private function getPageById($id, $params) {
        $mediawikiPage = Title::newFromId($id);
//        error_log($mediawikiPage);
        if ($mediawikiPage !== null) {
            $reptxthxPage = ReptxThxPage::newFromId($id);

            return $reptxthxPage;
        } else {
            $this->dieUsage('Page with id ' . $params['pageId'] . ' does not exists', 'invalidPageId');
        }
    }

    protected function getExamplesMessages() {
        return array(
//            'action=query&list=example'
//            => 'apihelp-query+example-example-1',
//            'action=query&list=example&key=do'
//            => 'apihelp-query+example-example-2',
        );
    }

}
