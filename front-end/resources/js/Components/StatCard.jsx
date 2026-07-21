import '../../css/stat-card.css';

export default function StatCard({ label, tokens, spend, color }) {
  return (
    <div className={`stat-card stat-card-${color}`}>
      <div className="stat-card-label">{label}</div>
      <div className="stat-card-numbers">
        <div className="stat-card-value">{Math.round(tokens).toLocaleString()} tokens</div>
        <div className="stat-card-spend">Spend: ${spend.toFixed(3)}</div>
      </div>
    </div>
  );
}