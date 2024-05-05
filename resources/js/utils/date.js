/**
 * Transforms date to a string with the format: "Wed, Jan 20, 2021"
 * @param {*} date 
 * @returns 
 */
const dayFormatted = (date) => {
  let day = date;
  if (typeof date === "string") {
    day = new Date(date);
  }
  return Intl.DateTimeFormat("ca-es", {
    weekday: "short",
    month: "short",
    day: "numeric",
    year: "numeric",
  }).format(day);
};

/**
 * Transforms date to a string with the format: "2021-01-20"
 */
const ymd = (date) => {
  if (!date) {
    return "";
  }
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
};

export { dayFormatted, ymd };
