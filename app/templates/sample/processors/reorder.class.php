<?php
/**
 * Reorder items
 *
 * @package <%= lowCaseName %>
 * @subpackage processors
 */
class <%= name %>ReorderItemUpdateProcessor extends modObjectProcessor {
    public $classKey = '<%= name %>Item';
    public $languageTopics = array('<%= lowCaseName %>:default');
    public $objectType = '<%= lowCaseName %>.item';

    public function process(){
        $idItem = $this->getProperty('idItem');
        $oldIndex = $this->getProperty('oldIndex');
        $newIndex = $this->getProperty('newIndex');


        $items = $this->modx->newQuery($this->classKey);
        $items->where(array(
                "id:!=" => $idItem,
                "position:>=" => min($oldIndex, $newIndex),
                "position:<=" => max($oldIndex, $newIndex),
            ));

        $items->sortby('position', 'ASC');

        $itemsCollection = $this->modx->getCollection($this->classKey, $items);

        if(min($oldIndex, $newIndex) == $newIndex){
            foreach ($itemsCollection as $item) {
                $itemObject = $this->modx->getObject($this->classKey, $item->get('id'));
                $itemObject->set('position', $itemObject->get('position') + 1);
                $itemObject->save();
            }
        }else{
            foreach ($itemsCollection as $item) {
                $itemObject = $this->modx->getObject($this->classKey, $item->get('id'));
                $itemObject->set('position', $itemObject->get('position') - 1);
                $itemObject->save();
            }
        }

        $itemObject = $this->modx->getObject($this->classKey, $idItem);
        $itemObject->set('position', $newIndex);
        $itemObject->save();


        return $this->success('', $itemObject);
    }

}
return '<%= name %>ReorderItemUpdateProcessor';
