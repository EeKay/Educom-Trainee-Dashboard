import '../../css/stat-card.css';

export default function StatCard({ label, value, color }) {
  return (
    <div className={`stat-card stat-card-${color}`}>
      <div className="stat-card-label">{label}</div>
      <div className="stat-card-value">{value}</div>
    </div>
  );
}