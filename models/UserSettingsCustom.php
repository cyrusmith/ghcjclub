<?
class UserSettingsCustom extends UserSettings {
    function __construct($userId) {
        $this
            ->addProperty('birthday', 'date')
            ->addProperty('birthdayVisible', 'enum', "'false','true'", 'false')
            ->addProperty('phone', 'varchar', '32')
            ->addProperty('phoneVisible', 'enum', "'false','true'", 'false')
            ->addProperty('skype', 'varchar', '32')
            ->addProperty('skypeVisible', 'enum', "'false','true'", 'false')
            ->addProperty('icq', 'varchar', '16')
            ->addProperty('icqVisible', 'enum', "'false','true'", 'false')
            ->addProperty('site', 'varchar', '128')
            ->addProperty('siteVisible', 'enum', "'false','true'", 'false')
            ->addProperty('vkontakte', 'varchar', '64')
            ->addProperty('vkontakteVisible', 'enum', "'false','true'", 'false')
            ->addProperty('commentMailTrack', 'enum', "'false','true'", 'true')
            ->addProperty('commentMailOther', 'enum', "'false','true'", 'true')
            ->addProperty('commentMailComment', 'enum', "'false','true'", 'true')
            ->addProperty('commentMailArticle', 'enum', "'false','true'", 'true')
            ->addProperty('commentMailUser', 'enum', "'false','true'", 'true')
            ->addProperty('commentMailGroupComment', 'enum', "'false','true'", 'true')
        ;
        parent::__construct($userId);
    }
}
