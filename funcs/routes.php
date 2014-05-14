<?
function setRoutes() {
    /*
     * AUTH
     */
	Router::get()->connect(Router::$PUT, '^api/auth', 'AuthCtrl/login');
	Router::get()->connect(Router::$DELETE, '^api/auth', 'AuthCtrl/logout');

	Router::get()->connect(Router::$GET, '^dev$', null, 'views/dev/index.html');
	Router::get()->connect(Router::$GET, '^dev/post/(?<route>.+)$', null, 'views/dev/post.html');

	Router::get()->connect(Router::$GET, '^styles', 'StylesCtrl/lists');
	Router::get()->connect(Router::$GET, '^tracks$', 'TracksCtrl/listsFiltered');

	Router::get()->connect(Router::$GET, '^tracks/(?<id>\d+)$', 'TracksCtrl/show');
	Router::get()->connect(Router::$GET, '^tracks/promo$', 'TracksCtrl/listsPromo');
	Router::get()->connect(Router::$GET, '^tracks/best$', 'TracksCtrl/listsBest');
	Router::get()->connect(Router::$GET, '^tracks/latest$', 'TracksCtrl/listsLatest');

    Router::get()->connect(Router::$POST, '^playlists/new$', 'PlaylistsCtrl/actionNew'); //t
    Router::get()->connect(Router::$POST, '^playlists/update$', 'PlaylistsCtrl/actionUpdate'); //t
    Router::get()->connect(Router::$DELETE, '^playlists/delete$', 'PlaylistsCtrl/actionDelete');
    Router::get()->connect(Router::$POST, '^playlists/tracks/set$', 'PlaylistsCtrl/actionTracksSet'); //t
    Router::get()->connect(Router::$GET, '^playlists/list$', 'PlaylistsCtrl/actionList');//t
    Router::get()->connect(Router::$GET, '^playlists/tracks/list$', 'PlaylistsCtrl/actionTracksList'); //t

    Router::get()->connect(Router::$GET, '^projects$', 'ProjectsCtrl/lists');
	Router::get()->connect(Router::$GET, '^projects/(?<id>\d+)$', 'ProjectsCtrl/show');

	Router::get()->connect(Router::$GET, '^projects/(?<projectId>\d+)/albums/?$', 'AlbumsCtrl/lists');
	Router::get()->connect(Router::$GET, '^albums/(?<id>\d+)$', 'AlbumsCtrl/show');

	Router::get()->connect(Router::$GET, '^projects/(?<projectId>\d+)/tracks', 'TracksCtrl/listsFiltered');
	Router::get()->connect(Router::$GET, '^projects/(?<projectId>\d+)/albums/(?<albumId>\d+)$', 'TracksCtrl/listsFiltered');

	Router::get()->connect(Router::$GET, '^tops$', 'TopsCtrl/lists');
	Router::get()->connect(Router::$GET, '^top/(?<topId>\d+)$', 'TracksCtrl/listsFiltered');

	Router::get()->connect(Router::$GET, '^users$', 'UsersCtrl/lists');
	Router::get()->connect(Router::$GET, '^users/online$', 'UsersCtrl/listsOnline');
	Router::get()->connect(Router::$GET, '^users/(?<id>\d+)$', 'UsersCtrl/show');

	Router::get()->connect(Router::$GET, '^blogs$', 'BlogsCtrl/lists');
    Router::get()->connect(Router::$POST, '^blogs/add$', 'BlogsCtrl/addPost');
	Router::get()->connect(Router::$GET, '^blogs/(?<id>\d+)$', 'BlogsCtrl/show');

	Router::get()->connect(Router::$GET, '^radio/(?<channel>\d)$', 'RadioCtrl/getInfo');
	Router::get()->connect(Router::$GET, '^comments/(?<objectType>.+)/(?<id>\d+)$', 'CommentsCtrl/lists');
	Router::get()->connect(Router::$POST, '^comments/(?<objectType>.+)/(?<id>\d+)', 'CommentsCtrl/create');

	// все остальные
	Router::get()->connect(Router::$GET, '^', null, 'views/main/main.html');
	/*
	 * GUEST
	 */
	if (0) {
		// старое
		Router::get()->connect(Router::$POST, ':controller/:action');
		Router::get()->connect(Router::$GET, '^$', null, 'views/main/index.html');


		Router::get()->connect(Router::$GET, '^albums/(?<id>\d+)$', 'Album/show', 'views/main/');

		Router::get()->connect(Router::$GET, '^articles$', 'Article/lists', 'views/main/');
		Router::get()->connect(Router::$GET, '^articles/(?<id>\d+)$', 'Article/show', 'views/main/');


		Router::get()->connect(Router::$GET, '^authors$', 'CjclubUser/lists', 'views/main/authorListView.php');
		Router::get()->connect(Router::$GET, '^authors/(?<id>\d+)$', 'CjclubUser/show', 'views/main/');

		Router::get()->connect(Router::$GET, '^tops$', 'Top/lists', 'views/main/');
		Router::get()->connect(Router::$GET, '^tops/(?<id>\d+)$', 'Top/show', 'views/main/');



		Router::get()->connect(Router::$GET, '^ExternalAuth/(login|logout)+(/)?$', 'ExternalAuth/reload', 'views/main/');
	}
    /*
     * Static Pages
     */
    $pages = (new Menu)->getStaticPages();
    foreach ($pages as $page)
        Router::get()->connect(Router::$GET, '^'.$page.'$', 'Menu/show', 'views/main/');

    /*
     * AUTHORIZE USER
     */
    if (ObjectsPool::get('RDS')->isLogged) {
        Router::get()->connect(Router::$GET, '^albums/new$', 'Album/show', 'views/main/');
        Router::get()->connect(Router::$GET, '^articles/new$', 'Article/show', 'views/main/');
        Router::get()->connect(Router::$GET, '^blogs/new$', 'Article/show', 'views/main/');
        Router::get()->connect(Router::$GET, '^tracks/new$', 'Track/show', 'views/main/');
        Router::get()->connect(Router::$GET, '^projects/new$', 'Project/show', 'views/main/');

        Router::get()->connect(Router::$GET, '^authors/(?<id>\d+)/cpanel$', 'FileContent/settingsView', 'views/main/');
        Router::get()->connect(Router::$GET, '^migrate$', 'FileContent/migrateView', 'views/main/');
        Router::get()->connect(Router::$GET, '^signup$', 'FileContent/index', 'views/main/');
    } else {
        Router::get()->connect(Router::$GET, '^signup$', 'CjclubUser/show', 'views/main/');
    }

	/*
	 * ADMIN SIDE, all CRUD is here
	 */
	if (ObjectsPool::get('RDS')->is('admin')) {
		$crudtpls = ['views/admin/crudlist3.php','views/admin/crudshow3.php'];
		Router::get()->connect(Router::$GET, '^admin/?$', null, 'views/admin/index.html');
        CRUD::createRoutesHelper('admin/articles(/)?', 'Article', $crudtpls);
        CRUD::createRoutesHelper('admin/eventTypes(/)?', 'EventType', $crudtpls);
        CRUD::createRoutesHelper('admin/groups(/)?', 'Group', $crudtpls);
        CRUD::createRoutesHelper('admin/jingles(/)?', 'Jingle', $crudtpls);
        CRUD::createRoutesHelper('admin/musicstyles(/)?', 'MusicStyle', $crudtpls);
        CRUD::createRoutesHelper('admin/pages(/)?', 'Menu', $crudtpls);
        CRUD::createRoutesHelper('admin/pics4users(/)?', 'Pics4User', $crudtpls);
        CRUD::createRoutesHelper('admin/radio(/)?', 'Radio', $crudtpls);
        CRUD::createRoutesHelper('admin/rating(/)?', 'Rating', $crudtpls);
        CRUD::createRoutesHelper('admin/serviceslist(/)?', 'Service', ['views/admin/servicesList.php','views/admin/crudshow3.php']);
        CRUD::createRoutesHelper('admin/shops(/)?', 'Shop', $crudtpls);
		CRUD::createRoutesHelper('admin/tags(/)?', 'Tag', $crudtpls);
		CRUD::createRoutesHelper('admin/tracks(/)?', 'Track', $crudtpls);
		CRUD::createRoutesHelper('admin/tops(/)?', 'Top', $crudtpls);
        CRUD::createRoutesHelper('admin/users(/)?', 'CjclubUser', $crudtpls);
        CRUD::createRoutesHelper('admin/sections(/)?', 'Section', $crudtpls);
		Router::get()->connect(Router::$GET, '^admin/serviceslist/(?<id>\d+)/users$', 'Service/getUsers', 'views/admin/crudlist3.php');
        Router::get()->connect(Router::$GET, '^admin/mails$', 'MailTplManager/lists', 'views/main/');
        Router::get()->connect(Router::$GET, '^admin/mails/(?<id>\d+)$', 'MailTplManager/showShort', 'views/main/');
        Router::get()->connect(Router::$GET, '^admin/mails/new$', 'MailTplManager/showShort', 'views/main/');
        Router::get()->connect(Router::$GET, '^admin/mails/(?<id>\d+)/full$', 'MailTplManager/show', 'views/main/');
	} else {
        foreach ((new Menu)->getArticlesNodes() as $node) {
            Router::get()->connect(Router::$GET, '^'.$node->foldername.'$', 'Article/listsNode', 'views/main/');
            Router::get()->connect(Router::$GET, '^'.$node->foldername.'/(?<id>\d+)$', 'Article/showNode', 'views/main/');
        }
	}
}