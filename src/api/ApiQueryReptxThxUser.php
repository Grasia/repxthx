<?php

class ApiQueryReptxThxUser extends ApiQueryBase {

    public function __construct($query, $moduleName) {
        parent::__construct($query, $moduleName, 'rxtu');
    }

    public function execute() {

        $params = $this->extractRequestParams();
        $user = null;
        if (isset($params['userName']) && isset($params['userId'])) {
            $this->dieUsage('Parameters userName and userId set at the same time', 'bothParamsSet');
        } else if (isset($params['userName'])) {
            $user = self::getUserByName($params['userName'], $params);
        } else if (isset($params['userId'])) {
            $user = self::getUserById($params['userId'], $params);
        } else {
            $this->dieUsage('userName parameter is not set', 'noUserName');
        }

        error_log($user);
        $rep = $user->getReputationValue();
        $cred = $user->getCreditValue();
        $lastRepUpd = $user->getRepLastUpdatedTimestamp();
        $lastCredUpd = $user->getCredLastUpdatedTimestamp();

        $r = array(
            'reputation' => $rep === null ? 0 : $rep,
            'credit' => $cred === null ? 0 : $cred,
            'lastRepUpdateTimestamp' => $lastRepUpd === null ? 0 : $lastRepUpd,
            'lastCredUpdateTimestamp' => $lastCredUpd === null ? 0 : $lastCredUpd
        );

        $this->getResult()->addValue(null, $this->getModuleName(), $r);
    }

    public function getAllowedParams() {
        return array(
            'userName' => array(
                ApiBase::PARAM_TYPE => 'string'
            ),
            'userId' => array(
                ApiBase::PARAM_TYPE => 'integer'
            )
        );
    }

    private function getUserByName($name, $params) {
        $mediawikiUserId = User::idFromName($name);

        if ($mediawikiUserId !== null) {
            $reptxthxUser = ReptxThxUser::newFromId($mediawikiUserId);

            return $reptxthxUser;
        } else {
            $this->dieUsage('User with name ' . $params['userName'] . ' does not exists', 'invalidUserName');
        }
    }

    private function getUserById($id, $params) {
        $mediawikiUser = User::newFromId($id);
        error_log($mediawikiUser);
        if (User::isValidUserName($mediawikiUser)) {
            $reptxthxUser = ReptxThxUser::newFromId($id);

            return $reptxthxUser;
        } else {
            $this->dieUsage('User with id ' . $params['userId'] . ' does not exists', 'invalidUserId');
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
