<?php

class Section extends CRUD {
    protected $modelClassName = 'SectionModel';

    /**
     * Получение списка разделов
     * @param $type тип раздела
     * @return DModelsCollection коллекция моделей разделов
     */
    function getListModel($type = '') {
        if (empty($type))
            $this->customize->conditions = '1';
        else $this->customize->conditions = "type='$type'";
        $this->customize->conditions .=  " ORDER BY parentId ASC, relOrder ASC";
        $model = parent::getListModel();
        return $model;
    }

    /**
     * Формирование карты формы для одной секции
     * @param DModel $model модель секции
     * @return array карта формы
     */
    function getShowModelToFormMap(DModel $model) {
        $map = parent::getShowModelToFormMap($model);
        if (RDS::get()->is('admin') && !empty($model->id)) {
            $map['parentId'] = 'SectionSelector';
        } elseif (empty($model->id))
            $map['parentId'] = 'HiddenInput';
        return $map;
    }

    /**
     * Настройка формы перед её генерацией
     * @param DModel $model модель секции
     */
    function getShowForm(DModel $model) {
        $form = parent::getShowForm($model);

        $labels = [
            'name'     => 'Название для url',
            'subject'  => 'Название раздела',
            'type'     => 'Тип раздела',
            'relOrder' => 'Порядковый номер',
            'parentId' => 'Родительская секция',
            'linkToListPage'     => ''//'Обратно',
        ];
        $type = '';
        foreach ($form as $element) {
            if ($element instanceof LabeledInput) {
                // Назначение меток
                $name = $element->getName();
                if ($name != 'linkToListPage')
                    $element->setLabel($labels[$name]);
                // Ограничение по типу только для уже созданных разделов
                if ($name == 'type' && !empty($model->id))
                    $type = $element->getValue();
                if ($name == 'parentId' && !empty($model->id))
                    $element->setType($type);
            }
        }

        return $form;
    }

    function validate(DModel $model) {
        parent::validate($model);
    }

    /**
     * Получение дерева разделов
     * @return array $matrix матрица поколений разделов
     */
    function getMatrix() {
        $sections = new DModelsCollection('SectionModel');
        $matrix = [];

        // Нулевое полколение
        $sections  = $sections->load('parentId IS NULL OR parentId = 0 OR parentId = id')->getAsStdObjects();
        $matrix[0] = [];
        $i   = 0;
        $ids = '';
        foreach ($sections as $section) {
            $matrix[0][$i] = $section;
            $ids .= $section->id.',';
            $i++;
        }
        $ids = substr($ids, 0, strlen($ids) - 1);

        // Остальные поколения
        $i = 1;
        while (count($sections) > 0) {
            $sections   = (new DModelsCollection('SectionModel'))
                            ->load("parentId IN ($ids) AND parentId <> id ORDER BY parentId")
                            ->getAsStdObjects();
            if (count($sections) == 0)
                break;

            $matrix[$i] = [];
            $j   = 0;
            $ids = '';

            foreach ($sections as $section) {
                $matrix[$i][$j] = $section;
                $ids .= $section->id.',';
                $j++;
            }
            $ids = substr($ids, 0, strlen($ids) - 1);

            $i++;
        }

        unset($sections);
        unset($ids);

        return $matrix;
    }

    /**
     * Построение html-кода для списка секций
     * (возможно, имеет смысл запилить это безобразие, как отдельный кэш-слот)
     * @param $sections матрица секций
     * @return string многоуровневый html-список
     */
    function getMatrixUl($sections) {
        $matrix = [];
        $count  = count($sections);

        // Формирование матрицы списков по родительским id-шникам
        for ($i = $count - 1; $i > 0; $i--) {
            $matrix[$i] = [];
            for($j = 0; $j < count($sections[$i]); $j++) {
                if (!isset($matrix[$i][$sections[$i][$j]->parentId]))
                    $matrix[$i][$sections[$i][$j]->parentId] = '<ul class="am_submenu">';

                $matrix[$i][$sections[$i][$j]->parentId] .= '<li class="am_submenu_item">
                    <a href="javascript:void(0)" class="am_submenu_link" onclick=" $(\'input[name=sectionId]\').val('.$sections[$i][$j]->id.');';
                if (isset($matrix[$i + 1]) && isset($matrix[$i + 1][$sections[$i][$j]->id]))
                    $matrix[$i][$sections[$i][$j]->parentId] .= '$(this).parent().find(\'.am_submenu\').toggleClass(\'hide\');">'.
                        $sections[$i][$j]->subject.'</a>'.$matrix[$i + 1][$sections[$i][$j]->id];
                else
                    $matrix[$i][$sections[$i][$j]->parentId] .= '">'.$sections[$i][$j]->subject.'</a>';
                $matrix[$i][$sections[$i][$j]->parentId] .= '</li>';

                // Есть notice, там где @, с несуществующим индексом j+1
                if (!isset($sections[$i][$j + 1]) || $matrix[$i][$sections[$i][$j]->parentId] != @$matrix[$i][$sections[$i][$j + 1]->parentId])
                    $matrix[$i][$sections[$i][$j]->parentId] .= '</ul>';

                // Убираем уже не нужные ряды потомков
                if (isset($matrix[$i + 2]))
                    unset($matrix[$i + 2]);
            }
        }

        // Добавление начальных элементов, у которых нет родителей
        // с разделением на две колонки
        $matrix[0][0] = '<ul class="articles_menu">';
        $count = count($sections[0]);
        for ($i = 0; $i < $count; $i++) {
            $matrix[0][0] .= '<li class="am_item"><a href="javascript:void(0)" class="am_link" onclick="$(\'input[name=sectionId]\').val('.$sections[0][$i]->id.');';

            if (isset($matrix[1]) && isset($matrix[1][$sections[0][$i]->id]))
                $matrix[0][0] .= '$(this).parent().find(\'.am_submenu\').toggleClass(\'hide\');">'.
                    $sections[0][$i]->subject.'</a>'.$matrix[1][$sections[0][$i]->id];
            else
                $matrix[0][0] .= '">'.$sections[0][$i]->subject.'</a>';
            $matrix[0][0] .= '</li>';

            if ($count > 1 && $i == ($count/2 - 1))
                $matrix[0][0] .= '</ul><ul class="articles_menu">';
        }
        $matrix[0][0] .= '</ul>';

        $html = $matrix[0][0];

        unset($matrix);
        unset($count);

        return $html;
    }

    /**
     * Перетаскивание статей из раздела в раздел
     * @param $from идентификатор раздела "Откуда"
     * @param $to идентификатор раздела "Куда"
     * @param $id идентификатор статьи
     * @return handlingResult сообщение о завершении работы метода
     */
    function moveFromTo($from, $to, $id = 0) {
        $from = filter_var($from, FILTER_SANITIZE_NUMBER_INT);
        $to   = filter_var($to, FILTER_SANITIZE_NUMBER_INT);
        $id   = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        if (empty($id)) {
            $articles = (new DModelsCollection('ArticleModel'))->load("sectionId = $from");
            if (!$articles->count())
                return (new handlingResult)->setStatus('В заданном разделе нет статей');
            else {
                foreach ($articles as $unit) {
                    $unit->sectionId = $to;
                    $unit->save();
                }
                return (new handlingResult)->setStatus('Статьи перенесены в заданный раздел');
            }
        } else {
            $article = (new ArticleModel)->load("id = $id");
            $article->sectionId = $to;
            $article->save();
            return (new handlingResult)->setStatus('Статья успешно перенесена');
        }
    }

    /**
     * Удаление раздела
     * @param $id идентификатор раздела
     * @return handlingResult сообщение о завершении работы метода
     */
    function delete($id = NULL) {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        // Убираем статьи из раздела
        $articles = new DModelsCollection('ArtcileModel');
        $articles->load("sectionId = $id");
        if ($articles->count())
            foreach ($articles as $article) {
                $article->sectionId = 0;
                $article->save();
            }

        // Пробегаемся по детям
        $childs = new DModelsCollection('SectionModel');
        $childs->load("parentId = $id");
        if ($childs->count())
            foreach ($childs as $child) {
                $child->parentId = 0;
                $child->save();
            }
        parent::delete($id);
        return (new handlingResult)->setStatus('Секция удалена');
    }

    /**
     * Установка родительского раздела
     * @param $id идентификатор раздела "Дитя"
     * @param $parent идентификатор раздела "Родитель"
     * @return handlingResult сообщение о завершении работы метода
     */
    function setParent($id, $parent) {
        $id     = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $parent = filter_var($parent, FILTER_SANITIZE_NUMBER_INT);
        $model  = (new SectionModel)->load("id = $id");

        $model->parentId = $parent;
        $model->save();

        return (new handlingResult)->setStatus('Подраздел создан');
    }
}