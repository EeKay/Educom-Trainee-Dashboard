import { useState } from 'react';
import Navbar from '../Components/NavbarAdmin';
import ProfileCard from '../Components/ProfileCard';
import LeaderboardAdmin from '../Components/LeaderboardAdmin';
import StatCard from '../Components/StatCard';
import DateRangeCard from '../Components/DateRangeCard';
import ChatBot from '../Components/ChatBot';
import '../../css/dashboard.css';
import { router } from '@inertiajs/react';

function formatDate(date) {
  return date.toISOString().split('T')[0];
}

export default function DashboardAdmin(props) {
  const users = props.users;
  const currentUser = props.currentUser; // server-confirmed selected user (used by ProfileCard, which needs real fetched data)

  const monthlyStats = props.user_monthly_usage;
  const dailyStats = props.user_daily_usage;
  const weeklyStats = props.user_weekly_usage;

  const leaderboardUsers = (props.users_leaderboard ?? []).map((entry) => ({
    id: entry.user_id,
    name: entry.name,
    tokensUsed: entry.tokens,
  }));

  // Chart data + its loading state
  const [modelStats, setModelStats] = useState([]);
  const [resultStats, setResultStats] = useState([]);
  const [loading, setLoading] = useState(false);

  const [selectedUserId, setSelectedUserId] = useState(
    props.currentUser?.id ?? null
  );

  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');

  function selectUser(userId) {
    setSelectedUserId(userId); 

    router.get(`/dashboard-admin/${userId}`, {}, {
      preserveState: true,
      preserveScroll: true,
      only: ['user_daily_usage', 'user_weekly_usage', 'user_monthly_usage', 'currentUser'],
      onSuccess: () => console.log('updated user', userId),
    });
  }

  function handleRangeChange(newStartDate, newEndDate, userId) {
    setStartDate(newStartDate);
    setEndDate(newEndDate);
    if (!userId || !newStartDate || !newEndDate) return;

    const today = formatDate(new Date());
    if (newStartDate > today || newEndDate > today) {
      console.warn('Date range cannot extend beyond today.');
      return;
    }
    if (newEndDate < newStartDate) {
      console.warn('End date cannot be before start date.');
      return;
    }

    setLoading(true);
    fetch(`/api/range-usage-admin?current_user=${userId}&start_date=${newStartDate}&end_date=${newEndDate}`)
      .then((res) => res.json())
      .then((data) => {
        setModelStats(data.models ?? []);
        setResultStats(data.results ?? []);
      })
      .catch((err) => console.error('Error fetching range usage:', err))
      .finally(() => setLoading(false)); // always clear loading, success or failure
  }

  return (
    <div>
      <Navbar />

      <div className="dashboard-container">

        <div className="dashboard-top-row">
          <div className="dashboard-profile">
            <ProfileCard
              user={currentUser}
              monthlySpend={monthlyStats.spend}
            />
          </div>
          <div className="dashboard-leaderboard">
            <LeaderboardAdmin
              users={leaderboardUsers}
              onSelectUser={selectUser}
              currentUser={selectedUserId}
            />
          </div>
        </div>

        <div className="dashboard-bottom-row">
          <div className="dashboard-stat">
            <StatCard
              label="total today"
              tokens={dailyStats.tokens}
              spend={dailyStats.spend}
              color="red"
            />
        </div>

        <div className="dashboard-stat">
          <StatCard
            label="total this week"
            tokens={weeklyStats.tokens}
            spend={weeklyStats.spend}
            color="blue"
          />
        </div>

        <div className="dashboard-date-range">
          <DateRangeCard
            modelStats={modelStats}
            resultStats={resultStats}
            onRangeChange={handleRangeChange}
            maxDate={formatDate(new Date())}
            currentUser={selectedUserId}
            loading={loading}
          />
        </div>
      </div>

    </div>
      <ChatBot />
    </div>
  );
}