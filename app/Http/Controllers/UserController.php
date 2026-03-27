<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
class UserController extends Controller
{
    // 1. LẤY DANH SÁCH & TÌM KIẾM SINH VIÊN (Read)
    public function index(Request $request)
    {
        // Khởi tạo query chỉ lấy những người có role = 0 (Sinh viên)
        $query = User::where('role', 0)->select('id', 'name', 'email', 'phone','class');

        // Kiểm tra xem Frontend có gửi từ khóa tìm kiếm lên không
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;
            // Tìm kiếm tương đối (LIKE) trên 3 cột
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('phone', 'LIKE', '%' . $keyword . '%');
            });
        }

        // Thực thi query và trả về kết quả mới nhất lên đầu
        $students = $query->orderBy('id', 'desc')->get();
        return response()->json($students);
    }

    // 2. THÊM MỚI SINH VIÊN (Create)
    public function store(Request $request)
    {
        // Ràng buộc dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'class' => 'nullable|string|max:50', // Thêm dòng kiểm tra lớp học
        ]);

        $student = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'class' => $request->class, // Thêm dòng lưu lớp học
            'password' => Hash::make('123456'), 
            'role' => 0 
        ]);
        return response()->json([
            'message' => 'Thêm sinh viên thành công!',
            'student' => $student
        ], 201); // 201 là mã HTTP báo hiệu đã tạo mới thành công
    }

    // 3. CẬP NHẬT THÔNG TIN (Update)
    public function update(Request $request, $id)
    {
        $student = User::find($id);

        if (!$student) {
            return response()->json(['message' => 'Không tìm thấy sinh viên!'], 404);
        }

        // Ràng buộc dữ liệu (bỏ qua kiểm tra trùng email với chính user hiện tại)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'class' => 'nullable|string|max:50', // Thêm dòng kiểm tra lớp học
        ]);

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'class' => $request->class, // Thêm dòng cập nhật lớp học
        ]);

        return response()->json([
            'message' => 'Cập nhật thông tin thành công!',
            'student' => $student
        ]);
    }

    // 4. XÓA SINH VIÊN (Delete)
    public function destroy($id)
    {
        $student = User::find($id);

        if (!$student) {
            return response()->json(['message' => 'Không tìm thấy sinh viên!'], 404);
        }

        $student->delete();

        return response()->json([
            'message' => 'Đã xóa sinh viên thành công!'
        ]);
    }
    public function import(Request $request)
    {
        // Bắt buộc phải gửi file lên và file phải có đuôi xlsx, xls hoặc csv
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'Vui lòng chọn file Excel.',
            'file.mimes' => 'Định dạng file không hợp lệ. Chỉ nhận file .xlsx, .xls, .csv'
        ]);

        try {
            // Thực thi việc đọc và lưu vào Database
            Excel::import(new UsersImport, $request->file('file'));
            
            return response()->json([
                'message' => 'Import danh sách sinh viên thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình Import: ' . $e->getMessage()
            ], 500);
        }
    }
}