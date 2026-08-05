import { useState, useEffect } from 'react';
import Navbar from '../Components/Navbar';
import ProfileCard from '../Components/ProfileCard';
import LeaderboardAdmin from '../Components/LeaderboardAdmin';
import StatCard from '../Components/StatCard';
import DateRangeCard from '../Components/DateRangeCard';
import ChatBot from '../Components/ChatBot';
import '../../css/dashboard.css';
import { setLayoutProps } from '@inertiajs/react';
import {router} from '@inertiajs/react';

function formatDate(date) {
  return date.toISOString().split('T')[0];
}

export default function DashboardAdmin(props) {
  const users = props.users;
  const currentUser = props.currentUser;

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

  function selectUser(currentUser){
    router.get(`/dashboard-admin/${currentUser}`, {}, {
      preserveState: true,
      preserveScroll: true,
      only: ['user_daily_usage', 'user_weekly_usage', 'user_monthly_usage','currentUser'],
      onSuccess: () => {console.log('updated user', currentUser);
      },
    })
  }

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

    fetch(`/api/range-usage?current_user=${currentUser}&start_date=${startDate}&end_date=${endDate}`)
      .then((res) => res.json())
      .then((data) => {
        console.log('Fetched range usage:', data);
        setModelStats(data.models ?? []);
        setResultStats(data.results ?? []);
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
            <LeaderboardAdmin users={leaderboardUsers} onSelectUser={selectUser} currentUser = {currentUser}/>
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
                maxDate={formatDate(new Date())}
                currentUser = {currentUser}

              />
          </div>
        </div>
      </div>
      <ChatBot />
    </div>
  )
}