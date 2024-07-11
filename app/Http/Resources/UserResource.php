<?php

namespace App\Http\Resources;

use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {

        $qualifications = $this->qualification;

        $this->qualification = $qualificationsWithName = [];
        if($qualifications) {
            $qualificationsWithName = array_map(function ($qualification) {
                if (isset($qualification['qualification_id'])) {
                    $qualificationDetail = Qualification::find($qualification['qualification_id']);
                    $qualification['qualification_name'] = $qualificationDetail ? $qualificationDetail->name : 'Unknown';
                }
                return $qualification;
            }, $qualifications);
        }

        $this->qualification = $qualificationsWithName;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'receive_email_notification' => $this->receive_email_notification,
            'receive_sms_notification' => $this->receive_sms_notification,
            'registration_number' => $this->registration_number,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            //'year_of_passed_out' => $this->year_of_passed_out,
            'address' => $this->address,
            'pincode' => $this->pincode,
            //'school' => $this->school,
            'city' => $this->city,
            //'city' => new CityResource($this->city),
            //'qualification_id' => $this->qualification_id,
            'qualification' => $this->qualification,//new QualificationResource($this->qualification),
            //'qualification' => new QualificationResource($this->qualification),
            'state_id' => $this->state_id,
            'country_code' => $this->country_code,

            'state' => new StateResource($this->state),
            'aadhaar_number' => $this->aadhaar_number,
            'linkedin_profile' => $this->linkedin_profile,
            'upload_resume' => $this->upload_resume ? url("storage/" . $this->upload_resume) : "",
            'upload_aadhar' => $this->upload_aadhar ? url("storage/" . $this->upload_aadhar) : "",
            'avatar_url' => $this->avatar_url ? url("storage/" . $this->avatar_url) : "",
            'branch' => new BranchResource($this->batches->select('id', 'name')),
            'domain'=>$this->domain_id
            // Add any additional fields as needed
        ];
    }
}
