import { Link } from '@inertiajs/react';
import {useState} from 'react';
import '../../css/user-menu.css';

export default function UserMenu() {
    const[isOpen, setIsOpen] = useState(false);
  return (
    <div className = "user-menu">
        <button className = "user-avatar" onClick={() => setIsOpen(!isOpen)}>
            USER
        </button>

        {isOpen && (
          <div className = "user-dropdown">
            <Link href="/change-password" className = "user-dropdown-link">Change Password</Link>
            <Link href="/change-email" className = "user-dropdown-link">Change Email</Link>
            <Link href="/logout" className = "user-dropdown-link">Logout</Link>
          </div>
        )}
    </div>
  );
}