import { useState, useEffect } from 'react';
import Navbar from '../Components/Navbar';
import ProfileCard from '../Components/ProfileCard';
import Leaderboard from '../Components/Leaderboard';
import StatCard from '../Components/StatCard';
import DateRangeCard from '../Components/DateRangeCard';
import '../../css/dashboard.css';

export default function Dashboard({ user, leaderboardUsers }) {
  
  //all placeholders 
  
  const currentUser = user || {
    id: 2,
    name: 'Sandy',
    avatar: 'https://placecats.com/200/200',
    tokensUsed: 4500,
    spend: 0.22
  };


  const users = leaderboardUsers || [
    { id: 1, name: 'Bobbie', avatar: 'https://placecats.com/201/201', tokensUsed: 6200 },
    { id: 2, name: 'Sandy', avatar: 'https://placecats.com/200/200', tokensUsed: 4500 },
    { id: 3, name: 'Carl', avatar: 'https://placecats.com/204/204', tokensUsed: 1800 },
  ];

  const [dailyStats] = useState({tokens: 27000, spend: 0.02});
  const [weeklyStats] = useState({tokens: 100000, spend: 0.1});
  const [rangeStats, setRangeState] = useState({tokens: 0, spend: 0});
  // const [aiUsage, setAiUsage] = useState(null);
  // useEffect(() => {
  //   fetch('http://127.0.0.1:9000/api/ai')
  //     .then(response => response.text())
  //     .then(text => {
  //       console.log(text); 
  //       const data = JSON.parse(text);
  //       setAiUsage(data);
  //     })
  //     .catch(error => console.error('Error fetching AI usage:', error));
  // }, []);

  function handleRangeChange(startDate, endDate) {
    console.log(`Selected range: ${startDate} to ${endDate}`);
  }

    return (
      <div>
        <Navbar />
        <div className="dashboard-container">
          <div style={{ display: 'flex', gap: '40px', alignItems: 'stretch', width: '100%' }}>
            <ProfileCard user={currentUser} />
            <div style={{ flex: 1 }}>
              <Leaderboard users={users} currentUserId={currentUser.id} />
            </div>
          </div>

          <div style={{ display: 'flex', gap: '24px', justifyContent: 'center', width: '100%' }}>
            <StatCard label="total per day" tokens={dailyStats.tokens} spend={dailyStats.spend} color="red" />
            <StatCard label="total per week" tokens={weeklyStats.tokens} spend={weeklyStats.spend} color="blue" />
            <DateRangeCard onRangeChange={handleRangeChange} />
          </div>
        
                {/* <div style={{ marginTop: '32px' }}>
                  <h3>AI Usage</h3>
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: '16px' }}>
                    {aiUsage ? (
                      aiUsage.map(item => (
                        <div
                          key={item.id}
                          style={{
                            border: '1px solid #ddd',
                            borderRadius: '8px',
                            padding: '16px',
                            minWidth: '220px',
                            boxShadow: '0 1px 3px rgba(0,0,0,0.1)',
                          }}
                        >
                          <p style={{ fontWeight: 'bold', marginBottom: '4px' }}>
                            {item.model.split('/').pop()}
                          </p>
                          <p>Date: {new Date(item.date).toLocaleDateString()}</p>
                          <p>Tokens: {item.tokens.toLocaleString()}</p>
                          <p>Spend: ${item.spend.toFixed(4)}</p>
                        </div>
                      ))
                    ) : (
                      <p>Loading...</p>
                    )}
                  </div>
                </div>
                <p> aiUsage: {aiUsage ? JSON.stringify(aiUsage) : 'Loading...'}</p>
                </div>
             */}
            
            </div>
        </div>
  );
}