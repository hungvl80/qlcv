<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Validation\Validator;

class RowTable extends Entity
{
    protected array $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * Xây dựng Validator động dựa trên thông tin cột.
     * @param \Cake\Validation\Validator $validator Đối tượng validator hiện tại.
     * @param array $columns Mảng các đối tượng cột (ví dụ: từ ColumnAliases).
     * @return \Cake\Validation\Validator
     */
    public static function buildValidator(Validator $validator, $columns): Validator
    {
        foreach ($columns as $col) {
            $field = $col->column_name;
            $type = $col->data_type;

            // Áp dụng các quy tắc kiểm tra kiểu dữ liệu
            // Mặc định cho phép NULL bằng cách không thêm requirePresence và notEmptyString
            // validatePresence và allowEmptyString sẽ cho phép trường là NULL hoặc chuỗi rỗng
            // nếu không có dữ liệu được gửi đến.
            switch ($type) {
                case 'int':
                case 'tinyint':
                    $validator->integer($field)->allowEmptyString($field); // Cho phép chuỗi rỗng, sau đó sẽ được cast thành null/0 tùy kiểu
                    break;
                case 'float':
                    $validator->numeric($field)->allowEmptyString($field);
                    break;
                case 'date':
                    $validator->date($field)->allowEmptyString($field);
                    break;
                case 'datetime':
                    $validator->dateTime($field)->allowEmptyString($field);
                    break;
                case 'email':
                    $validator->email($field)->allowEmptyString($field);
                    break;
                case 'file':
                    // Đối với file, Validator mặc định của CakePHP sẽ xử lý nếu không có file mới được tải lên.
                    // Nếu bạn muốn kiểm tra các thuộc tính của file (kích thước, loại, v.v.),
                    // bạn sẽ cần các quy tắc tùy chỉnh tại đây, nhưng allowEmptyFile() là cần thiết.
                    // Lưu ý: allowEmptyFile() chỉ áp dụng cho trường hợp không có file nào được chọn.
                    // Nó không kiểm tra string null/empty sau khi file đã được xử lý.
                    // Phần xử lý file đã được thực hiện trong controller, nên validator này chủ yếu là để catch các lỗi cơ bản.
                    $validator->add($field, 'fileUpload', [
                        'rule' => function ($value, $context) {
                            // Nếu không có file được tải lên (hoặc file bị lỗi UPLOAD_ERR_NO_FILE), coi là hợp lệ (cho phép null)
                            if ($value === null || ($value instanceof UploadedFile && $value->getError() === UPLOAD_ERR_NO_FILE)) {
                                return true;
                            }
                            // Nếu có đối tượng UploadedFile khác null và không có lỗi, coi là hợp lệ tại bước này.
                            // Các kiểm tra sâu hơn (loại, kích thước) được thực hiện trong controller.
                            if ($value instanceof UploadedFile && $value->getError() === UPLOAD_ERR_OK) {
                                return true;
                            }
                            // Trường hợp khác (có lỗi upload) thì không hợp lệ
                            return false;
                        },
                        'message' => 'Lỗi khi tải lên tệp tin.',
                        'allowEmpty' => true // Điều này sẽ cho phép trường là null hoặc không có dữ liệu
                    ]);
                    break;
                default: // bao gồm cả 'varchar', 'text'
                    $validator->scalar($field)->allowEmptyString($field);
                    break;
            }

            // Loại bỏ dòng này để luôn cho phép NULL/chuỗi rỗng.
            // if (!$col->null) {
            //     $validator->requirePresence($field)->notEmptyString($field);
            // }
        }

        return $validator;
    }
}