import "../../css/leaderboard.css";
import userIcon from '../../img/icon.png';

export default function LeaderboardAdmin({ users, onSelectUser, currentUser }) {
  if (!users?.length) return null;

  // Lowest tokens = furthest left
  const sortedUsers = [...users].sort(
    (a, b) => a.tokensUsed - b.tokensUsed
  );
  const best = sortedUsers[0].tokensUsed;
  const worst = sortedUsers[sortedUsers.length - 1].tokensUsed;
  const range = worst - best || 1;

  // Group users that land on the exact same token value,
  // so we know how many share a spot and each one's index within that group
  const groups = {};
  sortedUsers.forEach((user) => {
    if (!groups[user.tokensUsed]) groups[user.tokensUsed] = [];
    groups[user.tokensUsed].push(user);
  });

  const AVATAR_SPACING_PX = 50;

  return (
    <div className="leaderboard">
      <h2 className="leaderboard-title"><b>Leaderboard</b></h2>
      <div className="leaderboard-track">
        <div className="leaderboard-marker">
          <div className="leaderboard-start" />
        </div>
        <div className="leaderboard-timeline">
          <div className="leaderboard-line" />
          {sortedUsers.map((user) => {
            const percent = (user.tokensUsed - best) / range;
            const left = 10 + percent * 80;
            const isCurrent = user.id === currentUser;

            // find this user's position within their same-token group
            const group = groups[user.tokensUsed];
            const indexInGroup = group.findIndex((u) => u.id === user.id);
            const groupSize = group.length;

            // center the group around 0, spacing each member out evenly
            const offsetPx =
              groupSize > 1
                ? (indexInGroup - (groupSize - 1) / 2) * AVATAR_SPACING_PX
                : 0;

            return (
              <div
                key={user.id}
                className={`leaderboard-entry ${
                  isCurrent ? "leaderboard-entry-you" : ""
                }`}
                style={{
                  left: `${left}%`,
                  transform: `translateX(${offsetPx}px)`,
                }}
              >
                <div className="leaderboard-avatar-wrapper">

                    <img
                      src={userIcon}
                      alt={user.name}
                      className="leaderboard-avatar"
                      onClick = {() => onSelectUser(user.id)}
                      style = {{cursor:'pointer'}}
                    />
        
                  <div className="leaderboard-tooltip">
                    {user.tokensUsed} tokens
                  </div>
                </div>
                <div className="leaderboard-name">
                  {user.name.split(" ")[0]}
                </div>
              </div>
            );
          })}
          <div className="leaderboard-end" />
        </div>
      </div>
    </div>
  );
}