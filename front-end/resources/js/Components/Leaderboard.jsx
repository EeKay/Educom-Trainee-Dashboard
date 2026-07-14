import '../../css/leaderboard.css';
import crown from '../../img/Crown.png';

const MIN_GAP = 80;
const MAX_GAP = 320;

export default function Leaderboard({ users, currentUserId }) {
  const sortedUsers = [...users].sort((a, b) => b.tokensUsed - a.tokensUsed);
  const highest = sortedUsers[0].tokensUsed;
  const lowest = sortedUsers[sortedUsers.length - 1].tokensUsed;
  const range = highest - lowest || 1;

  return (
    <div className="leaderboard">
      <b><h2 className="leaderboard-title">Leaderboard</h2></b>
      <div className="leaderboard-track">
        <div className="leaderboard-marker">
          <svg className="leaderboard-marker-shape" viewBox="0 0 120 60" style={{ overflow: 'visible' }}>
            <polygon
              points="50,0 50,60 120,30"
              fill="#3DAEDA"
              stroke="#3DAEDA"
              strokeWidth="12"
              strokeLinejoin="round"
            />
          </svg>
          <span className="leaderboard-marker-text">this month</span>
        </div>

        {sortedUsers.map((user, index) => {
          let gap = MIN_GAP;
          if (index > 0) {
            const previousUser = sortedUsers[index - 1];
            const diff = previousUser.tokensUsed - user.tokensUsed;
            const ratio = diff / range;
            gap = MIN_GAP + ratio * (MAX_GAP - MIN_GAP);
          }

          const isCurrentUser = user.id === currentUserId;

          return (
            <div
              key={user.id}
              className={`leaderboard-entry ${isCurrentUser ? 'leaderboard-entry-you' : ''}`}
              style={{ marginLeft: `${gap}px` }}
            >
              <div className="leaderboard-avatar-wrapper">
                <img src={user.avatar} alt={user.name} className="leaderboard-avatar" />
                <div className="leaderboard-tooltip">{user.tokensUsed} tokens</div>
              </div>
              {index === sortedUsers.length - 1 && (
                <img src={crown} alt="Crown" className="leaderboard-crown" />
              )}
              <div className="leaderboard-name">{user.name}</div>
            </div>
          );
        })}
      </div>
    </div>
  );
}

