<?php 

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IgnoreIfDataIsEqualValidation implements Rule {
    /**
     * nama table
     * 
     * @var string
     */
    private $tableName;
    /**
     * nilai lama
     * 
     * @var string
     */
    private $oldValue;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($tableName,$oldValue)
    {
        $this->tableName=$tableName;
        $this->oldValue=$oldValue;
    }
        /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes ($attribute, $value) {
        $bool=false;        
        if (strtolower($value) == strtolower($this->oldValue)) {
            $bool=true;
        }else{
            $bool = !(\DB::table($this->tableName)->where($attribute,$value)->count() > 0);
        }     
        return $bool;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message () {
        return 'Mohon maaf data yang anda inputkan sudah tersedia. Mohon ganti dengan yang lain';
    }
}