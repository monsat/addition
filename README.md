# Addition Behavior

This is a CakePHP's Behavior.
You can use it to count up DB field's value ( without using counter cache )

## Require

 - CakePHP2.0 or higher (2.0 branch)
 - MySQL

## USAGE

```php
# bootstrap.php
CakePlugin::load('Addition'); // or CakePlugin::loadAll();

# AnyModel
class User extends AppModel {
  public $actsAs = array('Addition.Addition' => array('fields' => array('views'))); // this option is default
  
  public countUp($id, $inc = 1) {
    $this->sum($id, $inc); // or $this->sum($id) ... to increment views field value
  }
}
```

```php
# AnyController
public function counter($id = null) {
  // +2 (default fields [views])
  $this->User->sum($id, 2);
  // +1 (field [views]) and  +1 (field [another])
  $this->User->sum($id, 1, array('views', 'another'));
  // +1 (field [first]) and  +2 (field [second]) and  +3 (field [third])
  $this->User->sum($id, array(1, 2, 3), array('first', 'second', 'third'));
  // +1 (field [first]) and  +2 (fields [second, third, forth])
  $this->User->sum($id, array(1, 2), array('first', 'second', 'third', 'forth'));
}
```