<?
/*
 * Прокси для лучших статей
 */
class ArticleModelProxyForBest extends DModelProxyDatabaseJoins {
    function __construct() {
        parent::__construct('articles');
        $this->addJoinedTable('articles_has_tags', 'articles_has_tags.article_id = articles.id');
    }
    function read($params = null) {
        $args = null;
        if (!empty($params)) {
            $tags  = $params['tags'];
            $tags  = explode(',', $tags);
            $tags  = implode("','", $tags);
            $where = "code IN ('$tags')";

            if ($params['invertTags'] !== false) {
                $invertTags = explode(',', $params['invertTags']);
                $invertTags = implode("','", $invertTags);
                $where     .= " AND code NOT IN ('$invertTags')";
            }
            $tagQuery = dbSelect('tags', 'id', $where, DB_GETQUERY);
            $ids      = dbSelect('articles_has_tags', 'DISTINCT(article_id)', "tag_id IN ($tagQuery)", DB_SELECT_COL);
            $args     = 'articles_has_tags.tag_id IN ('.implode(',', $ids).') ORDER BY articles.public_date DESC';

            $this->pager->capacity = $params['limit'];
        }
        return parent::read($args);
    }

}