const dayFormatted = (date) => {
  let day = date;
  if (typeof date === "string") {
    day = new Date(date);
  }
  return Intl.DateTimeFormat("ca-es", {
    weekday: "long",
    month: "short",
    day: "numeric",
    year: "numeric",
  }).format(day);
};

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
