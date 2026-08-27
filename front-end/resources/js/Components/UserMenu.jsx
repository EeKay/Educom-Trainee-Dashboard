import { Link } from '@inertiajs/react';
import {useState} from 'react';
import '../../css/user-menu.css';
import userIcon from '../../img/icon.png';

export default function UserMenu() {
    const[isOpen, setIsOpen] = useState(false);
  return (
    <div className = "user-menu">
        <button className = "user-avatar" onClick={() => setIsOpen(!isOpen)}>
            <img src= {userIcon} alt="User Avatar" />
        </button>

    {/* //work in progress */}
        <div className={`user-dropdown ${isOpen ? 'user-dropdown-open' : ''}`}>
          <Link href="/passwordReset" className = "user-dropdown-link">Change Password</Link>
          <Link href="/emailReset" className = "user-dropdown-link">Change Email</Link>
          <Link href="/login" className = "user-dropdown-link">Logout</Link>
        </div>
      
    </div>
  );
}