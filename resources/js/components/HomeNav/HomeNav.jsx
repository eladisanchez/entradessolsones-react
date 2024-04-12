import styles from "./HomeNav.module.scss";
import { Icon } from "@/components/ui";
import { Link } from "@inertiajs/react";
import classNames from "classnames";

const HomeNav = ({ view = "list" }) => {
  return (
    <div className={styles.homenav}>
      <div className={styles.nav}>
        <a href="#activitats">Activitats turístiques</a>
        <a href="#esdeveniments">Teatre, concerts i esdeveniments</a>
      </div>
      <div className={styles.buttons}>
        <Link
          href="/"
          className={classNames(styles.button_list, {
            [styles.current]: view == "list",
          })}
        >
          <Icon icon="list" />
        </Link>
        <Link
          href="/calendari"
          className={classNames(styles.button_calendar, {
            [styles.current]: view == "calendar",
          })}
        >
          <Icon icon="calendar" />
        </Link>
      </div>
    </div>
  );
};

export default HomeNav;
