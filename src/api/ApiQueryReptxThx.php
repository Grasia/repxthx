<?php

class ApiQueryReptxThx extends ApiQueryBase {

    public function __construct($query, $moduleName) {
        parent::__construct($query, $moduleName, 'rxt');
    }

    public function execute() {

        $params = $this->extractRequestParams();
        $r = array('reptxthx' => 'test');
        // This is a filtered request, only show this key if it exists,
        // (or none, if it doesn't exist)
        if (isset($params['queryType'])) {
            $queryType = $params['queryType'];
            $r['queryType'] = $queryType;
        } 
        
         if (isset($params['userName'])) {
            $queryType = $params['userName'];
            $r['userName'] = $queryType;
        } 
        
         if (isset($params['pageTitle'])) {
            $queryType = $params['pageTitle'];
            $r['pageTitle'] = $queryType;
        } 


        $this->getResult()->addValue(null, $this->getModuleName(), $r);
    }

    public function getAllowedParams() {
        return array(
            'queryType' => array(
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => 'true'
            ),
            'userName' => array(
                ApiBase::PARAM_TYPE => 'string'
            ),
            'pageTitle' => array(
                ApiBase::PARAM_TYPE => 'string'
            )
        );
    }

    protected function getExamplesMessages() {
        return array(
            'action=query&list=example'
            => 'apihelp-query+example-example-1',
            'action=query&list=example&key=do'
            => 'apihelp-query+example-example-2',
        );
    }

}
