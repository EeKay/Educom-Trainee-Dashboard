import { useEffect, useRef, useState } from "react";
import "../../css/leaderboard.css";
import userIcon from "../../img/icon.png";

const MIN_SLOT_WIDTH = 120;
const BASE_GAP = 90;
const YOU_GAP_BONUS = 16;
const EDGE_PADDING = 50;

// Push entries apart when they get too close
function resolveCollisions(idealPositions, entries) {
  const n = idealPositions.length;
  const positions = [...idealPositions];

  const gapBefore = (i) =>
    BASE_GAP +
    (entries[i].isYou || entries[i - 1].isYou
      ? YOU_GAP_BONUS
      : 0);

  // Forward pass
  for (let i = 1; i < n; i++) {
    if (
      positions[i] - positions[i - 1] <
      gapBefore(i)
    ) {
      positions[i] =
        positions[i - 1] + gapBefore(i);
    }
  }

  // Backward pass
  for (let i = n - 2; i >= 0; i--) {
    if (
      positions[i + 1] - positions[i] <
      gapBefore(i + 1)
    ) {
      positions[i] =
        positions[i + 1] - gapBefore(i + 1);
    }
  }

  return positions;
}

export default function Leaderboard({
  users,
  currentUserId,
}) {
  const scrollRef = useRef(null);
  const [containerWidth, setContainerWidth] = useState(0);

  useEffect(() => {
    if (!scrollRef.current) return;

    const ro = new ResizeObserver((entries) => {
      setContainerWidth(
        entries[0].contentRect.width
      );
    });

    ro.observe(scrollRef.current);

    return () => ro.disconnect();
  }, []);

  if (!users?.length) return null;

  // Lowest tokens = furthest left = best
  const sortedUsers = [...users].sort(
    (a, b) => a.tokensUsed - b.tokensUsed
  );

  const best = sortedUsers[0].tokensUsed;
  const worst =
    sortedUsers[sortedUsers.length - 1].tokensUsed;

  const range = worst - best || 1;

  // Tie-aware ranking
  let rank = 0;
  let lastTokens = null;

  const ranked = sortedUsers.map((user, i) => {
    if (user.tokensUsed !== lastTokens) {
      rank = i + 1;
      lastTokens = user.tokensUsed;
    }

    return {
      ...user,
      rank,
      isYou: user.id === currentUserId,
    };
  });

  const naturalWidth =
    EDGE_PADDING * 2 +
    Math.max(0, ranked.length - 1) * BASE_GAP +
    78;

  const trackWidth = Math.max(
    containerWidth,
    ranked.length * MIN_SLOT_WIDTH
  );

  const idealPositions = ranked.map(
    (user) =>
      EDGE_PADDING +
      ((user.tokensUsed - best) / range) *
        (trackWidth - EDGE_PADDING * 2)
  );

  const positions = resolveCollisions(
    idealPositions,
    ranked
  );

  return (

    <div className="leaderboard">
      <h2 className="leaderboard-title">
        <b>Leaderboard</b>
      </h2>

      <div className="leaderboard-track">
        <div
          className="leaderboard-scroll"
          ref={scrollRef}
        >
          <div
            className="leaderboard-timeline"
            style={{ width: trackWidth }}
          >
            <div className="leaderboard-line" />

            <div className="leaderboard-start" />

            {ranked.map((user, i) => (
              <div
                key={user.id}
                className={`leaderboard-entry ${
                  user.isYou
                    ? "leaderboard-entry-you"
                    : ""
                }`}
                style={{
                  left: positions[i],
                  animationDelay: `${i * 40}ms`,
                }}
                title={user.name}
              >
                <div className="leaderboard-avatar-wrapper">
                  <div className="leaderboard-avatar-frame">
                    <img 
                      src={userIcon}
                      alt={user.name}
                      className="leaderboard-avatar"
                    />
                  </div>

                  <div className="leaderboard-tooltip">
                    {user.tokensUsed.toLocaleString()} tokens
                  </div>
                </div>

                <div className="leaderboard-name">
                  {(user.name || "Unknown").split(" ")[0]}
                </div>
              </div>
            ))}

            <div className="leaderboard-end" />
          </div>
        </div>
      </div>

    </div>
  );
}