<?php
class ArticleModelProxyForTag extends DModelProxyDatabase {
    function __construct() {
        parent::__construct('articles');
    }
    function read($tags = null) {
        if (!empty($tags)) {
            $tags  = explode(',', $tags);
            $tags  = implode("','", $tags);
            $where = "code IN ('$tags')";

            $tagQuery = dbSelect('tags', 'id', $where, DB_GETQUERY);
            $ids      = dbSelect('articles_has_tags', 'DISTINCT(article_id)', "tag_id IN ($tagQuery)", DB_SELECT_COL);
            if (!empty($ids))
                return parent::read('id IN ('.implode(',', $ids).') ORDER BY public_date DESC');
        }
        return parent::read('id = NULL');
    }

}
