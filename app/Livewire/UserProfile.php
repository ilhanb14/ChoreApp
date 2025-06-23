<?php


namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public User $user;
    public $editing = false;
    public $name;
    public $password;
    public $password_confirmation;
    public $families;
    
    // Remove $currentUser and $isAdmin as properties since we'll compute them
    // protected $listeners = ['refreshFamilies' => 'loadFamilies'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'password' => 'nullable|confirmed|min:8',
    ];

    public function mount($userId = null)
    {
        $this->user = $userId ? User::findOrFail($userId) : Auth::user();
        $this->name = $this->user->name;
        $this->loadFamilies();
    }

    public function loadFamilies()
    {
        $this->families = $this->user->families()
            ->with(['users' => function($query) {
                $query->orderBy('name');
            }])
            ->get();
    }

    // Helper method to get current user
    public function getCurrentUserProperty()
    {
        return Auth::user();
    }

    // Helper method to check if admin
    public function getIsAdminProperty()
    {
        return $this->user->is_admin;
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function save()
    {
        $this->validate();

        $this->user->name = $this->name;
        
        if ($this->password) {
            $this->user->password = bcrypt($this->password);
        }

        $this->user->save();
        $this->editing = false;
        
        session()->flash('message', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}