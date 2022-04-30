import puppeteer from 'puppeteer';

main();

async function main() {
  console.log('Scraping topics... \n');
  const associatedTopics = await scrape('nodejs');

  // Calculate the repetition of every associated topic
  const accumulator = associatedTopics.reduce((acc, topic) => {
    acc[topic] = acc[topic] ? acc[topic] + 1 : 1;
    return acc;
  }, {});
  const topics = Object.entries(accumulator);
  topics.sort((a, b) => b[1] - a[1]);
  topics.forEach(([name, occurrence]) => {
    console.log(`${name}: ${occurrence}`);
  });

  process.exit(0);
}

async function scrape(topic) {
  let browser;
  try {
    browser = await puppeteer.launch();
    const url = `https://github.com/topics/${topic}`;

    const page = await browser.newPage();
    await page.goto(url);

    const elements = await page.$$(
      'article.border.rounded.color-shadow-small.color-bg-subtle.my-4'
    );

    const texts = await Promise.all(elements.map(getAssociatedTopics));

    const topics = texts.flat().filter((text) => text && text !== topic);

    return topics;
  } catch (err) {
    console.log(`Error scraping ${topic}`);
    console.error(err);
  } finally {
    await browser.close();
  }
}

async function getAssociatedTopics(element) {
  const relativeTime = await element.$eval(
    'relative-time',
    (element) => element.textContent
  );
  // Giving the relativeTime, we can check if it is after one month
  const possibleString = [
    'yesterday',
    'today',
    'hours',
    'seconds',
    'minutes',
    'days',
  ];
  const isAfterOneMonth = possibleString.some((string) =>
    relativeTime.includes(string)
  );
  let associatedTopics;
  if (isAfterOneMonth) {
    associatedTopics = await element.$$eval(
      'a.topic-tag.topic-tag-link.f6.mb-2',
      (nodes) => nodes.map((node) => node.innerText)
    );
  }

  return associatedTopics;
}
