<?function setUserRules(){
	AuthController::setModelName('UserDefaultModel');
	$authUser = new UserDefaultModel;
	$authUser->load();
	$RDS = ObjectsPool::get('RDS')->init($authUser);
    if (isset($RDS->userId)) {
		// todo переделать, запутанно все
		$RDS->userInfo = (new UserModel)->load($RDS->userId);//->getAsStdObject();
		$RDS->userInfo = $RDS->userInfo->getAsStdObject();
		$RDS->type = ['user'];
    }
	if (!$RDS->isLogged) {
		$RDS->type = ['guest'];
	}
	foreach($RDS->type as $type){
		setUserRulesByType($type);
	}
	setUserRulesByType(null);
}
function setUserRulesByType($type){
	$RDS = ObjectsPool::get('RDS');
    if (empty($type) && $RDS->isLogged)
        $type = 'user';
	$RDS->add($type);
	switch ($type){
		case 'cli':
			break;

		case 'admin':
			$RDS->add('admin');

            /*
             * Advert
             */
            $RDS->add('access.Advert.addSection');
            $RDS->add('access.Advert.removeSection');
            /*
             * CjclubUser
             */
            $RDS->add('access.CjclubUser.show.new');
            $RDS->add('access.CjclubUser.delete');
            /*
             * EventType
             */
            $RDS->add('access.EventType.edit');
            $RDS->add('access.EventType.show.new');
            $RDS->add('access.EventType.delete');
            /*
             * Jingle
             */
            $RDS->add('access.Jingle.lists');
            $RDS->add('access.Jingle.edit');
            $RDS->add('access.Jingle.show');
            $RDS->add('access.Jingle.show.new');
            $RDS->add('access.Jingle.delete');
            /*
             * MailTplManager
             */
            $RDS->add('access.MailTplManager');
            $RDS->add('access.mails');
            /*
             * Menu
             */
            $RDS->add('access.Menu.edit');
            $RDS->add('access.Menu.show.new');
            $RDS->add('access.Menu.delete');
            /*
             * MusicStyle
             */
            $RDS->add('access.MusicStyle.edit');
            $RDS->add('access.MusicStyle.show.new');
            $RDS->add('access.MusicStyle.delete');
            /*
             * Pics4User
             */
            $RDS->add('access.Pics4User.edit');
            $RDS->add('access.Pics4User.show.new');
            $RDS->add('access.Pics4User.delete');
            /*
             * Radio
             */
            $RDS->add('access.Radio.edit');
            $RDS->add('access.Radio.show.new');
            $RDS->add('access.Radio.delete');
            /*
             * Rating
             */
            $RDS->add('access.Rating.lists');
            $RDS->add('access.Rating.edit');
            $RDS->add('access.Rating.show');
            $RDS->add('access.Rating.show.new');
            $RDS->add('access.Rating.delete');
            /*
             * Release
             */
            $RDS->add('access.Release.edit');
            $RDS->add('access.Release.show.new');
            $RDS->add('access.Release.delete');
            /*
             * Section
             */
            $RDS->add('access.Section.lists');
            $RDS->add('access.Section.show');
            $RDS->add('access.Section.edit');
            /*
             * Service
             */
            $RDS->add('access.Service.show');
            $RDS->add('access.Service.edit');
            $RDS->add('access.Service.show.new');
            $RDS->add('access.Service.delete');
            $RDS->add('access.Service.getUsers');
            /*
             * Shop
             */
            $RDS->add('access.Shop.edit');
            /*
             * Tag
             */
            $RDS->add('access.Tag.edit');
            $RDS->add('access.Track.show.new');
            $RDS->add('access.Track.delete');
            /*
             * Top
             */
            $RDS->add('access.Top.edit');
            $RDS->add('access.Top.show.new');
            $RDS->add('access.Top.delete');

		case 'pro':
		case 'skilled':
		case 'advanced':
		case 'newbie':
		case 'user':
            /*
             * Album
             */
            $RDS->add('access.Album.edit');
            $RDS->add('access.Album.show.new');
            $RDS->add('access.Album.delete');
            /*
             * Article
             */
            $RDS->add('access.Article.edit');
            $RDS->add('access.Article.show.new');
            $RDS->add('access.Article.delete');
            /*
             * Comment
             */
            $RDS->add('access.Comment.edit');
            $RDS->add('access.Comment.show.new');
            $RDS->add('access.Comment.delete');
            /*
             * Content
             */
            $RDS->add('access.Content.edit');
            $RDS->add('access.Content.show.new');
            $RDS->add('access.Content.delete');
            /*
             * CjclubUser
             */
            $RDS->add('access.CjclubUser.doLogout');
            $RDS->add('access.CjclubUser.sendActivationCode');
            $RDS->add('access.CjclubUser.migrate');
            /*
             * ExternalAuth
             */
            $RDS->add('access.ExternalAuth.logout');
            /*
             * Service
             */
            $RDS->add('access.Service.lists');
            $RDS->add('access.Shop.show.new');
            $RDS->add('access.Shop.delete');
            /*
             * Project
             */
            $RDS->add('access.Project.edit');
            $RDS->add('access.Project.show.new');
            /*
             * Track
             */
            $RDS->add('access.Track.deleteRequest');
            $RDS->add('access.Track.edit');
            $RDS->add('access.Track.plusOne');

		case 'guest':
		default:
			$RDS->add('access.FileContent');
			/*
			 * Album
			 */
			$RDS->add('access.AlbumsCtrl.lists');
			$RDS->add('access.AlbumsCtrl.show');
                  $RDS->add('access.Album.getTracks');
			/*
			 * Article
			 */
                  $RDS->add('access.BlogsCtrl.addPost');
                  $RDS->add('access.BlogsCtrl.addTags');
			$RDS->add('access.BlogsCtrl.lists');
                  $RDS->add('access.BlogsCtrl.show');
//			$RDS->add('access.Article.listsNode');
//			$RDS->add('access.Article.showNode');
			/*
			 * Comment
			 */
			$RDS->add('access.CommentsCtrl.lists');
			$RDS->add('access.CommentsCtrl.create');
			/*
			 * Content
			 */
			$RDS->add('access.Conttent.list');
			$RDS->add('access.Connt.show');
			/*
			 * Country
			 */
			$RDS->add('access.Country.lists');
			$RDS->add('access.Country.show');
			/*
			 * CjclubUser
			 */
			$RDS->add('access.UsersCtrl.lists');
			$RDS->add('access.UsersCtrl.listsOnline');
			$RDS->add('access.UsersCtrl.show');

            $RDS->add('access.AuthCtrl.login');
            $RDS->add('access.AuthCtrl.logout');
            $RDS->add('access.CjclubUser.findForMigration');
            /*
             * EventType
             */
            $RDS->add('access.EventType.lists');
            $RDS->add('access.EventType.show');
            /*
             * ExternalAuth
             */
            $RDS->add('access.ExternalAuth');
            $RDS->add('access.ExternalAuth.login');
            $RDS->add('access.ExternalAuth.reload');
            /*
             * File
             */
            $RDS->add('access.File.getTrackPic');
            $RDS->add('access.File.getListenLink');
            $RDS->add('access.File.getAuthorAvatar');
            /*
             * Menu
             */
            $RDS->add('access.Menu.lists');
            $RDS->add('access.Menu.show');
            /*
             * MusicStyle
             */
            $RDS->add('access.MusicStyle.lists');
            $RDS->add('access.MusicStyle.show');
			/*
			 * Pics4User
			 */
			$RDS->add('access.Pics4User.lists');
			$RDS->add('access.Pics4User.show');
            /*
             * Project
             */
            $RDS->add('access.ProjectsCtrl.lists');
            $RDS->add('access.ProjectsCtrl.show');
			/*
			 * Radio
			 */
			$RDS->add('access.Radio.lists');
			$RDS->add('access.Radio.show');
			$RDS->add('access.RadioCtrl.getInfo');
			/*
			 * Release
			 */
			$RDS->add('access.Release.lists');
			$RDS->add('access.Release.show');
			/*
			 * Shop
			 */
			$RDS->add('access.Shop.lists');
			$RDS->add('access.Shop.show');
			/*
			 * Tag
			 */
			$RDS->add('access.Tag.lists');
			$RDS->add('access.Tag.show');
			/*
			 * Top
			 */
			$RDS->add('access.TopsCtrl.lists');
			/*
			 * Track
			 */
			$RDS->add('access.StylesCtrl.lists');

			$RDS->add('access.TracksCtrl.lists');
			$RDS->add('access.TracksCtrl.listsFiltered');
			$RDS->add('access.TracksCtrl.listsPromo');
			$RDS->add('access.TracksCtrl.listsBest');
			$RDS->add('access.TracksCtrl.listsLatest');
			$RDS->add('access.TracksCtrl.show');
            $RDS->add('access.Track.actionPlus');
            $RDS->add('access.Track.getPluses');

        /**
         * PlaylistCtrl
         * TODO: Разбросать по правам
         */
        $RDS->add('access.PlaylistsCtrl.actionNew');
        $RDS->add('access.PlaylistsCtrl.actionUpdate');
        $RDS->add('access.PlaylistsCtrl.actionDelete');
        $RDS->add('access.PlaylistsCtrl.actionTracksSet');
        $RDS->add('access.PlaylistsCtrl.actionTracksList');
        $RDS->add('access.PlaylistsCtrl.actionList');

        break;
	}
}
?>
