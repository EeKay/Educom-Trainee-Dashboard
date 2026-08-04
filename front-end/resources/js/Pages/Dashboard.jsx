import { useState, useEffect } from 'react';
import Navbar from '../Components/Navbar';
import ProfileCard from '../Components/ProfileCard';
import Leaderboard from '../Components/Leaderboard';
import StatCard from '../Components/StatCard';
import DateRangeCard from '../Components/DateRangeCard';
import ChatBot from '../Components/ChatBot';
import '../../css/dashboard.css';
import { setLayoutProps } from '@inertiajs/react';

function formatDate(date) {
  return date.toISOString().split('T')[0];
}

export default function Dashboard(props) {
  const users = props.users;
  const currentUser = 2; //hardcoded for now, but will be changed to token from the session 

  const monthlyStats = props.user_monthly_usage;
  const dailyStats = props.user_daily_usage;
  const weeklyStats = props.user_weekly_usage;
  const leaderboardUsers = (props.users_leaderboard ?? []).map((entry) => ({
    id: entry.user_id,
    name: entry.name,
    tokensUsed: entry.tokens,
  }));

  const [modelStats, setModelStats] = useState([]);
  const [resultStats, setResultStats] = useState([]);

  function handleRangeChange(startDate, endDate) {
    if (!currentUser || !startDate || !endDate) return;

    const today = formatDate(new Date());

    if (startDate > today || endDate > today) {
      console.warn('Date range cannot extend beyond today.');
      return;
    }
    if (endDate < startDate) {
      console.warn('End date cannot be before start date.');
      return;
    }

    fetch(`/api/range-usage?start_date=${startDate}&end_date=${endDate}`)
      .then((res) => res.json())
      .then((data) => {
        console.log('Fetched range usage:', data);
        setModelStats(data.models);
        setResultStats(data.results);
      })
      .catch((err) => console.error('Error fetching range usage:', err));
  }

  return (
    <div>
      <Navbar />
      <div className="dashboard-container">
        <div style={{ display: 'flex', gap: '40px', alignItems: 'stretch', width: '100%' }}>
          <ProfileCard user={currentUser} monthlySpend={monthlyStats.spend} />
          <div style={{ flex: 1 }}>
            <Leaderboard users={leaderboardUsers} currentUserId={currentUser} />
          </div>
        </div>

        <div style={{ display: 'flex', gap: '48px', width: '100%' }}>
          <div style={{ flex: '0 0 220px' }}>
            <StatCard label="total today" tokens={dailyStats.tokens} spend={dailyStats.spend} color="red" />
          </div>
          <div style={{ flex: '0 0 220px' }}>
            <StatCard label="total this week" tokens={weeklyStats.tokens} spend={weeklyStats.spend} color="blue" />
          </div>
            <div style={{ flex: 1 }}>
              <DateRangeCard
                modelStats={modelStats}
                resultStats={resultStats}
                onRangeChange={handleRangeChange}
                currentUser={currentUser}
                maxDate={formatDate(new Date())}
              />
          </div>
        </div>
      </div>
      <ChatBot />
    </div>
  )
}