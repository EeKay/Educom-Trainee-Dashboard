import { Link } from '@inertiajs/react';
import logo from '../../img/logo.svg';
import '../../css/navbar.css';
import UserMenu from '../Components/UserMenu';

export default function Navbar() {
  return (
    <nav className = "navbar">
      <Link href ="/dashboard" className = "logo-group">
        <img src={logo} alt="Educom" className = "logo-img" />
        <span className = "logo-text">educom</span>
      </Link>
      <div className = "nav-links">
        <Link href="/dashboard" className = "nav-link">Dashboard</Link>
        <Link href="/faq" className = "nav-link">FAQ</Link>
      </div>
      <div className = "user-menu-wrapper">
        <UserMenu />
      </div>
    </nav>
  );
}

