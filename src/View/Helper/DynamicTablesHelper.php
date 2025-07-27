<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\I18n\FrozenTime; // Cần thiết để xử lý kiểu dữ liệu datetime/date/time
use Cake\Utility\Security;
use Cake\Core\Configure; // Import Configure để đọc cấu hình

/**
 * DynamicTables Helper
 */
class DynamicTablesHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];

    protected array $tinyintBooleanMapping; // Thuộc tính để lưu trữ ánh xạ boolean

    /**
     * Khởi tạo Helper.
     *
     * @param \Cake\View\View $view The View instance.
     * @param array<string, mixed> $config Configuration settings.
     */
    public function __construct(View $view, array $config = [])
    {
        parent::__construct($view, $config);
        // Tải ánh xạ từ Configure trong quá trình khởi tạo helper
        $this->tinyintBooleanMapping = Configure::read('TinyintBooleanMapping') ?? [0 => 'Không', 1 => 'Có'];
    }

    /**
     * Định dạng giá trị ô trong bảng dựa trên kiểu dữ liệu của cột.
     *
     * @param mixed $value Giá trị gốc của ô.
     * @param string $dataType Kiểu dữ liệu của cột (ví dụ: 'string', 'integer', 'boolean', 'datetime', 'date', 'time', 'tinyint', 'file').
     * @return string Giá trị đã được định dạng dưới dạng chuỗi HTML an toàn.
     */
    public function formatCellValue($value, string $dataType): string
    {
        if (is_null($value)) {
            return ''; // Trả về chuỗi rỗng cho giá trị null
        }

        switch ($dataType) {
            case 'boolean':
            case 'tinyint': // Xử lý tinyint như boolean
                // Sử dụng ánh xạ được cấu hình cho các giá trị boolean
                // `h()` được dùng để escape HTML, đảm bảo an toàn XSS
                return h($this->tinyintBooleanMapping[$value] ?? (string)$value);
            case 'datetime':
            case 'timestamp':
                if (!($value instanceof FrozenTime)) {
                    try {
                        $value = new FrozenTime($value);
                    } catch (\Exception $e) {
                        return h((string)$value); // Trả về giá trị gốc nếu không thể chuyển đổi
                    }
                }
                return $value->format('d/m/Y H:i'); // Định dạng datetime thành dd/mm/yyyy HH:mm
            case 'date':
                if (!($value instanceof FrozenTime)) {
                    try {
                        $value = new FrozenTime($value);
                    } catch (\Exception $e) {
                        return h((string)$value); // Trả về giá trị gốc nếu không thể chuyển đổi
                    }
                }
                return $value->format('d/m/Y'); // Định dạng date thành dd/mm/yyyy
            case 'time':
                if (!($value instanceof FrozenTime)) {
                    try {
                        // Giả định giá trị 'time' có thể được chuyển đổi thành FrozenTime
                        // Ví dụ: '10:30:00' sẽ được xử lý
                        $value = new FrozenTime($value);
                    } catch (\Exception $e) {
                        return h((string)$value); // Trả về giá trị gốc nếu không thể chuyển đổi
                    }
                }
                return $value->format('H:i'); // Định dạng time thành HH:mm
            case 'file': // Thêm case cho kiểu dữ liệu file
                // Hiện tại, chỉ trả về tên file. Bạn có thể mở rộng để tạo link download tại đây nếu muốn.
                return h((string)$value);
            default:
                // Mặc định chuyển sang chuỗi và escape HTML để ngăn chặn XSS
                return h((string)$value);
        }
    }
}