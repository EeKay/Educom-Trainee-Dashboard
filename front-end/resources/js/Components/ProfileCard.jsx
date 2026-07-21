import '../../css/profile-card.css';

export default function ProfileCard({ user }) {
  return (
    <div className="profile-card">
      <img src={user.avatar} alt={user.name} className="profile-avatar" />
      <div className="profile-name">{user.name}</div>
      <div className="profile-tokens">
        total of <strong>{user.spend.toLocaleString()} euros </strong> spent this month
      </div>
    </div>
  );
}