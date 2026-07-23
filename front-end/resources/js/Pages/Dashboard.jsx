import { useState, useEffect } from 'react';
import Navbar from '../Components/Navbar';
import ProfileCard from '../Components/ProfileCard';
import Leaderboard from '../Components/Leaderboard';
import StatCard from '../Components/StatCard';
import DateRangeCard from '../Components/DateRangeCard';
import '../../css/dashboard.css';

const API_BASE = 'http://127.0.0.1:9000/api';
const CURRENT_USER_ID = 1; // TODO: replace with real logged-in user id once auth exists

function sumUsage(records) {
  return records.reduce(
    (totals, record) => ({
      tokens: totals.tokens + Number(record.tokens),
      spend: totals.spend + Number(record.spend),
    }),
    { tokens: 0, spend: 0 }
  );
}

function formatDate(date) {
  return date.toISOString().split('T')[0];
}

function getMondayOfThisWeek() {
  const now = new Date();
  const monday = new Date(now);
  monday.setDate(now.getDate() - ((now.getDay() + 6) % 7));
  return monday;
}

function getFirstOfThisMonth() {
  const now = new Date();
  return new Date(now.getFullYear(), now.getMonth(), 1);
}

export default function Dashboard() {

  const [users, setUsers] = useState([]);
  //const [currentUser, setCurrentUser] = useState(null);
  const currentUser = 1; //change later and change the calls to currentUser.id
  const [monthlyTokens, setMonthlyTokens] = useState(0);
  const [monthlySpend, setMonthlySpend] = useState(0);
  const [dailyStats, setDailyStats] = useState({ tokens: 0, spend: 0 });
  const [weeklyStats, setWeeklyStats] = useState({ tokens: 0, spend: 0 });
  const [rangeStats, setRangeStats] = useState({ tokens: 0, spend: 0 });
  const [leaderboardUsers, setLeaderboardUsers] = useState([]);

  //check which user you are, make later
  useEffect(() => {
    fetch(`${API_BASE}/users`)
      .then((res) => res.json())
      .then((allUsers) => {
        setUsers(allUsers);
        //const me = allUsers.find((u) => u.id === CURRENT_USER_ID);
        //setCurrentUser(me || allUsers[0]);
      })
      .catch((err) => console.error('Error fetching users:', err));
  }, []);

  //set the day/week/customizeable stats
  useEffect(() => {
    if (!currentUser) return;

    const today = formatDate(new Date());
    const monday = formatDate(getMondayOfThisWeek());
    const firstOfMonth = formatDate(getFirstOfThisMonth());

    fetch(`${API_BASE}/ai/period/user/${currentUser}?start_date=${today}&end_date=${today}`)
      .then((res) => res.json())
      .then((records) => setDailyStats(sumUsage(records)))
      .catch((err) => console.error('Error fetching daily usage:', err));

    fetch(`${API_BASE}/ai/period/user/${currentUser}?start_date=${monday}&end_date=${today}`)
      .then((res) => res.json())
      .then((records) => setWeeklyStats(sumUsage(records)))
      .catch((err) => console.error('Error fetching weekly usage:', err));

    fetch(`${API_BASE}/ai/period/user/${currentUser}?start_date=${firstOfMonth}&end_date=${today}`)
      .then((res) => res.json())
      .then((records) => {
        const totals = sumUsage(records);
        setMonthlyTokens(totals.tokens);
        setMonthlySpend(totals.spend);
    })
    .catch((err) => console.error('Error fetching monthly usage:', err));
  }, []);
  
  //get all users first and then per user and from those get the data per month.
  //get all users and token usage for the leaderboard
  useEffect(() => {
    if (users.length === 0) return;

    const firstOfMonth = formatDate(getFirstOfThisMonth());
    const today = formatDate(new Date());

    Promise.all(
      users.map((u) =>
        fetch(`${API_BASE}/ai/period/user/${u.id}?start_date=${firstOfMonth}&end_date=${today}`)
          .then((res) => res.json())
          .then((records) => ({
            id: u.id,
            name: u.name,
            tokensUsed: sumUsage(records).tokens,
          }))
      )
    )
    .then(setLeaderboardUsers)
    .catch((err) => console.error('Error building leaderboard:', err));
  }, [users]);

  // Validated: end date can't be before start date, and neither can be after today
  function handleRangeChange(startDate, endDate) {
    if (!currentUser || !startDate || !endDate) return;

    const today = formatDate(new Date());
    if (startDate > today || endDate > today) {
      console.warn('Date range cannot extend beyond today.');
      <div> 'Date range cannot extend beyond today. </div>
      return;
    }
    if (endDate < startDate) {
      console.warn('End date cannot be before start date.');
      <div> End date cannot be before start date </div>
      return;
    }

    fetch(`${API_BASE}/ai/period/user/${currentUser}?start_date=${startDate}&end_date=${endDate}`)
      .then((res) => res.json())
      .then((records) => setRangeStats(sumUsage(records)))
      .catch((err) => console.error('Error fetching range usage:', err));
  }

  return (
    <div>
      <Navbar />
      <div className="dashboard-container">
        <div style={{ display: 'flex', gap: '40px', alignItems: 'stretch', width: '100%' }}>
          <ProfileCard user={currentUser} monthlySpend={monthlySpend} />
          <div style={{ flex: 1 }}>
            <Leaderboard users={leaderboardUsers} currentUserId={currentUser} />
          </div>
        </div>

        <div style={{ display: 'flex', gap: '24px', justifyContent: 'center', width: '100%' }}>
          <StatCard label="total per day" tokens={dailyStats.tokens} spend={dailyStats.spend} color="red" />
          <StatCard label="total per week" tokens={weeklyStats.tokens} spend={weeklyStats.spend} color="blue" />
          <DateRangeCard
            tokens={rangeStats.tokens}
            spend={rangeStats.spend}
            onRangeChange={handleRangeChange}
            maxDate={formatDate(new Date())}
          />
        </div>
      </div>
    </div>
  )
}