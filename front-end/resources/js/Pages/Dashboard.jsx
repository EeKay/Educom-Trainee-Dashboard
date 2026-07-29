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
  const currentUser = 1;

  const monthlyStats = props.user_monthly_usage;
  const dailyStats = props.user_daily_usage;
  const weeklyStats = props.user_weekly_usage;
  const leaderboardUsers = (props.users_leaderboard ?? []).map((entry) => ({
    id: entry.user_id,
    name: entry.name,
    tokensUsed: entry.tokens,
  }));

  const [rangeStats, setRangeStats] = useState([]);

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
      .then(setRangeStats)
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
                rangeStats={rangeStats}
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

  // const [monthlyStats, setMonthlyStats] = useState({tokens: 0, spend: 0});
  // const [dailyStats, setDailyStats] = useState({ tokens: 0, spend: 0 });
  // const [weeklyStats, setWeeklyStats] = useState({ tokens: 0, spend: 0 });
  // const [leaderboardUsers, setLeaderboardUsers] = useState([]);

  // //get all users
  // useEffect(() => {
  //   fetch(`${API_BASE}/users`)
  //     .then((res) => res.json())
  //     .then((allUsers) => {
  //       setUsers(allUsers);
  //     })
  //     .catch((err) => console.error('Error fetching users:', err));
  // }, []);

  // //fetching daily, weekly and monthly usage
  // useEffect(() => {
  //   if (!currentUser) return;
  //   const today = formatDate(new Date());

  //   fetch(`${API_BASE}/ai/spend/period/user/${currentUser}?start_date=${today}&end_date=${today}`)
  //     .then((res) => res.json())
  //     .then(setDailyStats)
  //     .catch((err) => console.error('Error fetching daily usage:', err));

  //   fetch(`${API_BASE}/ai/spend/week/user/${currentUser}`)
  //     .then((res) => res.json())
  //     .then(setWeeklyStats)
  //     .catch((err) => console.error('Error fetching weekly usage:', err));

  //   fetch(`${API_BASE}/ai/spend/month/user/${currentUser}`)
  //     .then((res) => res.json())
  //     .then(setMonthlyStats)
  //   .catch((err) => console.error('Error fetching monthly usage:', err));
  // }, [currentUser]);
  

  // //every user's monthly total for the leaderboard
  // useEffect(() => {
  //   fetch(`${API_BASE}/ai/spend/month`)
  //     .then((res) => res.json())
  //     .then((allMonthly) => {
  //       const leaderboardData = allMonthly.map((entry) => ({
  //         id: entry.user_id,
  //         name: entry.name,
  //         tokensUsed: entry.tokens,
  //       }));
  //       setLeaderboardUsers(leaderboardData);
  //     })
  //     .catch((err) => console.error('Error building leaderboard:', err));
  // }, []);

  // Validated: end date can't be before start date, and neither can be after today


  //   fetch(`${API_BASE}/ai/spend/period/daily/user/${currentUser}?start_date=${startDate}&end_date=${endDate}`)
  //     .then((res) => res.json())
  //     .then(setRangeStats)
  //     .catch((err) => console.error('Error fetching range usage:', err));
