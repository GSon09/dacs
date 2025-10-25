<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryFullSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Sách Trong Nước (IDs: 1-7)
            'Văn học',
            'Kinh tế',
            'Tâm lý - kĩ năng sống',
            'Nuôi dạy con',
            'Sách thiếu nhi',
            'Tiểu sử - Hồi ký',
            'Sách mới',
            
            // VPP - Dụng Cụ Học Sinh (IDs: 8-13)
            'Bút',
            'Sổ tay',
            'Thước kẻ',
            'Gôm/Tẩy',
            'Bìa hồ sơ',
            'Dụng cụ khác',
            
            // Sách Giáo Khoa (IDs: 14-25)
            'Lớp 1','Lớp 2','Lớp 3','Lớp 4','Lớp 5','Lớp 6',
            'Lớp 7','Lớp 8','Lớp 9','Lớp 10','Lớp 11','Lớp 12',
            
            // Sách Học Ngoại Ngữ (IDs: 26-29)
            'Tiếng Anh',
            'Nhật',
            'Hoa',
            'Hàn',
        ];
        
        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
