export function setRating(results) {
  const occurrences = results.map((result) => result.occurrence);
  const highest = Math.max(...occurrences);
  const lowest = Math.min(...occurrences);

  results.forEach((result) => {
    const rating = ((result.occurrence - lowest) / (highest - lowest)) * 100;
    result.rating = rating;
  });
}
