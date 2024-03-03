import { Link } from "@inertiajs/react";

export default function Layout({ children }) {
    return (
        <main>
            <div className="site-top">
                <div className="container">
                    <div className="site-top__lang-nav">
                        <Link href="/ca/">CA</Link>
                        <Link href="/es/">ES</Link>
                    </div>
                    <div className="site-top__menu">
                        <Link href="/ca/">Calendari</Link>
                        <Link href="/ca/">Com puc vendre entrades?</Link>
                        <Link href="/ca/">Turisme Solsonès</Link>
                    </div>
                </div>
            </div>
            <header>
                <div className="title">Entrades Solsonès</div>
                <Link href="/">Home</Link>
                <Link href="/about">About</Link>
                <Link href="/contact">Contact</Link>
            </header>
            <article>{children}</article>
        </main>
    );
}
