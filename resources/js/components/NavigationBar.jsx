import { Link } from 'react-router-dom';

export function NavigationBar() {
    return (
        <nav className="navigation-bar">
            <ul>
                <li><Link to="/">Home</Link></li>
                <li><Link to="/import">Import</Link></li>
            </ul>
        </nav>
    );
}
