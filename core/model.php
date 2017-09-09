<?

abstract class Model{

    private static $model = array();

    protected static $default_data = array();

    protected $data;

    public function __construct() {

        self::registerModel($this);
    }

    public abstract function getData();
    public function commitData() {}

    public static function registerModel(Model $model) {

        array_push(self::$model, $model);
    }

    public static function start() {

        foreach(self::$model as $model)
            $model->commitData();
    }
}