import '../../css/profile-card.css';
// update this with backend JSON data
export default function ProfileCard({ user }) {
    return(
        <div className = "profile-card">
            <img src={user.avatar} alt="User Avatar" className = "profile-avatar" />
            <div className = "profile-name"> {user.name} </div>
            <div className = "profile-tokens"> total of <b>{user.tokensUsed} tokens </b> used this month</div> 
        </div>
    )
}