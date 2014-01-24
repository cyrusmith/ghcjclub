<?
/**
 * Вводится новое отображение поля author_id
 * из-за преобразования значения поля при чтении из базы
 * в models/ArticleModel и models/TrackModel (метод getterConversions)
 */
class AuthorIdView extends StaticInput {
    function getHtml() {
        if (isset($this->value))
            return "<a href='projects/{$this->value->id}'>{$this->value->name}</a>";
        else return '';
    }
}

/**
 * Вводится новое отображение поля country_id
 * из-за преобразования значения поля при чтении из базы
 * в models/CjclubUserModel (метод getterConversions)
 */
class CountryView extends StaticInput {
    function getHtml() {
        if (isset($this->value)) {
            $countries = new DModelsCollection('CountryModel');
            $countries->load('true ORDER BY name');
            return (new Selector)->addOptions($countries->getAsHash('name'))->setValue($this->value->id)->setName('country_id');
        } else return '';
    }
}

/**
 * Вводится новое отображение поля city_id
 * из-за преобразования значения поля при чтении из базы
 * в models/CjclubUserModel (метод getterConversions)
 */
class CityView extends StaticInput {
    function getHtml() {
        if (isset($this->value)) {
            $cities = new DModelsCollection('CityModel');
            $cities->load('true ORDER BY name');
            return (new Selector)->addOptions($cities->getAsHash('name'))->setValue($this->value->id)->setName('city_id');
        } else return '';
    }
}

/**
 * Отображение списка доступных проектов
 * для конкретного пользователя
 */
class ProjectSelector extends StaticInput {
    function getHtml() {
        if (isset($this->value)) {
            $projects = new DModelsCollection('ProjectModel');
            $projects->load('creatorId = '.RDS::get()->userId.' ORDER BY name');
            return (new Selector)->addOptions($projects->getAsHash('name'))->setValue($this->id)->setName('projectId');
        } else return '';
    }
}

/**
 * Отображение списка доступных
 * музыкальных стилей
 */
class MusicStylesSelector extends StaticInput {
    function getHtml() {
        if (isset($this->value)) {
            $projects = new DModelsCollection('MusicStyleModel');
            $projects->load('true ORDER BY value');
            return (new Selector)->addOptions($projects->getAsHash('value'))->setValue($this->id)->setName('style_id');
        } else return '';
    }
}

/**
 * Отображение списка доступных
 * фльбомов для текущего проекта
 */
class AlbumSelector extends StaticInput {
    function getHtml() {
        if (isset($this->value)) {
            $albums = new DModelsCollection('AlbumModel');
            $albums->load('projectId = '.RDS::get()->config->curProjectId.' ORDER BY name');
            if ($albums->count() > 0)
                return (new Selector)->addOptions($albums->getAsHash('name'))->setValue($this->value->id)->setName('album_id');
            else return 'В проекте нет альбомов';
        }
        return '';
    }
}

class SectionSelector extends StaticInput {
    function getHtml() {
        $sections = new DModelsCollection('SectionModel');
        $sections->load("type = '{$this->type}' ORDER BY subject");
        $this->value = empty($this->value) ? 0 : $this->value;
        if ($sections->count() > 0)
            return (new Selector)->addOptions($sections->getAsHash('subject'))->addOption('0', '')->setValue($this->value)->setName('parentId');
        else return 'Разделы отсутсвуют';
    }
}
/**
 * Пустое поле, не нуждающееся в отображении
 */
class EmptyView extends StaticInput {
    function getHtml() {
        return '';
    }
}
?>