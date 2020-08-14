# test-design

## 1) Возможно ли такое в PHP и как это реализуется, если возможно:
$obj = new Building();
$obj['name'] = 'Main tower';
$obj['flats'] = 100;
$obj->save();

Возможно, если реализовать интерфейс **ArrayAccess**:
```php
class Building implements ArrayAccess {
    
    public $name;
    public $flats;
    
    public function offsetExists($offset) {
        return isset($this->$offset);
    }
    public function offsetGet($offset) {
        return $this->$offset ?? null;
    }
    
    public function offsetSet($offset, $value) {
        $this->$offset = $value;
    }
    
    public function offsetUnset($offset) {
        unset($this->$offset);
    }
}
```
