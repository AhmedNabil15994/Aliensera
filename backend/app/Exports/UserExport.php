<?php 
namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class UserExport implements FromView,WithColumnWidths
{	

	public function __construct($student_id){
		$this->student_id = $student_id;
	}

    public function view() : View
    {	
    	return view('Dashboard.Views.statsTable', [
            'data' => User::generateObj(User::NotDeleted()->whereIn('id',$this->student_id),true)['data']
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 55,            
            'C' => 55,            
            'D' => 55,            
        ];
    }
}