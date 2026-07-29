import '../../css/profile-card.css';

export default function ProfileCard({ user, monthlySpend }) {
  const initial = user.name ? user.name.charAt(0).toUpperCase() : '?';

  return (
    <div className="profile-card">         
        <img className = "profile-avatar" src="https://placecats.com/200/200" alt="User Avatar" />
      <div className="profile-name">{user.name}</div>
      <div className="profile-tokens">
        total of <strong>€{Number.isFinite(parseFloat(monthlySpend)) ? parseFloat(monthlySpend).toFixed(2) : '0.00'}</strong> spent this month
      </div>
    </div>
  );
}