import Navbar from '../Components/Navbar';
import ProfileCard from '../Components/ProfileCard';
import Leaderboard from '../Components/Leaderboard';
import StatCard from '../Components/StatCard';
import '../../css/dashboard.css';

export default function Dashboard({ user, leaderboardUsers }) {
  const currentUser = user || {
    id: 2,
    name: 'Sandy',
    avatar: 'https://placecats.com/200/200',
    tokensUsed: 4500,
  };


  const users = leaderboardUsers || [
    { id: 1, name: 'Bobbie', avatar: 'https://placecats.com/201/201', tokensUsed: 6200 },
    { id: 2, name: 'Sandy', avatar: 'https://placecats.com/200/200', tokensUsed: 4500 },
    { id: 3, name: 'Carl', avatar: 'https://placecats.com/204/204', tokensUsed: 1800 },
  ];

  const avgWeek = 27000;
    const avgMonth = 100000;
    const avgYear = 1200000;

    return (
        <div>
        <Navbar />
            <div className="dashboard-container">
            <div style={{ display: 'flex', gap: '40px', alignItems: 'flex-start' }}>
                <ProfileCard user={currentUser} />
                <div style={{ flex: 1 }}>
                <Leaderboard users={users} currentUserId={currentUser.id} />
                </div>
            </div>

            <div style={{ display: 'flex', gap: '24px', justifyContent: 'center', marginTop: '32px' }}>
                <StatCard label="average per week" value={avgWeek} color="red" />
                <StatCard label="average per month" value={avgMonth} color="blue" />
                <StatCard label="average per year" value={avgYear} color="red" />
            </div>
            </div>
        </div>
  );
}