import "../../css/leaderboard.css";

export default function Leaderboard({ users, currentUserId }) {
  if (!users?.length) return null;

  // Lowest tokens = furthest left
  const sortedUsers = [...users].sort(
    (a, b) => a.tokensUsed - b.tokensUsed
  );

  const best = sortedUsers[0].tokensUsed;
  const worst = sortedUsers[sortedUsers.length - 1].tokensUsed;
  const range = worst - best || 1;

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
            const isCurrent = user.id === currentUserId;

            return (
              <div
                key={user.id}
                className={`leaderboard-entry ${
                  isCurrent ? "leaderboard-entry-you" : ""
                }`}
                style={{ left: `${left}%` }}
              >
                <div className="leaderboard-avatar-wrapper">
                  <img
                    src={"https://placecats.com/200/200"}
                    alt={user.name}
                    className="leaderboard-avatar"
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