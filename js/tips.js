document.addEventListener("DOMContentLoaded", function () {
    const tips = [
        // Budgeting & Expense Control (Financial Ninja)
        "Earning a lot is not enough—true wealth is what you manage to keep from what you earn.",
        "A household budget doesn't restrict your freedom; it gives it to you by showing where your money actually goes.",
        "Track every single penny spent. Awareness of small expenses is the first step to huge savings.",
        "Fixed costs are your commitments—the lower they are, the more life flexibility you have.",
        "Before making an impulse purchase, wait 48 hours. Often the urge to buy fades away on its own.",
        "Paying by card makes budgeting easier, provided you track your balance as meticulously as cash.",
        "Auditing your household subscriptions once a quarter can free up surprising sums annually.",
        "Don't cut costs to the bone by force—look for optimization where you don't lose quality of life.",
        "Treat saving as an 'expense' paid to yourself first, right after receiving your paycheck.",
        "The best budget is the one that is realistic to maintain, not the ideal one on paper.",
        
        // Emergency Fund & Security
        "An emergency fund is your peace of mind. Build a 3 to 6-month living reserve before investing.",
        "Keep your safety fund in an easily accessible savings account, not in risky assets.",
        "Insure only what could ruin your budget (health, life, civil liability).",
        "Consumer debt is a financial anchor holding you back. Pay it off first using the avalanche or snowball method.",
        "A credit card is a great tool only if you pay it off 100% within the grace period.",
        "Never invest money you will need in the next 3 to 5 years.",
        "Avoid loans for consumer goods (vacations, electronics). If you can't afford it—postpone the purchase.",
        "An emergency fund protects you from having to take out expensive loans during sudden crises.",
        "Working a 9-to-5 job carries the risk of a single income source. It's always smart to build reserves.",
        "Your health is your greatest capital—investing in prevention is the best insurance policy.",

        // Investing & Financial Fortress
        "Inflation is a silent thief—keeping all your savings in a 0% cash buffer means a real loss.",
        "Time in the market beats timing the market.",
        "Diversification is the only free lunch in the financial world. Don't put all your eggs in one basket.",
        "Stocks represent real, profit-generating businesses. Buying a broad index makes you a co-owner of hundreds of companies.",
        "Treasury bonds provide a safe anchor and stabilizer for any healthy investment portfolio.",
        "Investment costs matter immensely in the long run—choose low-fee index funds and ETFs.",
        "Market panic is an opportunity for patient investors, not a signal to sell at a loss.",
        "Gold in your portfolio is insurance against extreme crises, not a tool for getting rich quick.",
        "Understanding the risks you take is more important than potential returns.",
        "Automating investments (e.g., permanent ETF purchase orders) removes emotion from wealth building.",
        "Consistency in saving and investing beats market genius.",
        "Never invest in something you don't understand—the golden rule of every conscious investor.",
        "Compound interest is the eighth wonder of the world. The start date matters more than the amount.",
        "Speculation differs from investing in that it relies on hope rather than math and business fundamentals.",
        "Financial markets grow in the long run, despite numerous crises along the way.",

        // Psychology of Money & Mindset
        "True wealth is not about luxury cars and watches, but about assets that are hidden from view.",
        "Comparing yourself to others is the shortest path to financial frustration and unnecessary debt.",
        "Money buys independence, and independence lets you make better life decisions.",
        "Financial education is the best investment that always yields a 100% return.",
        "Making mistakes is human, but learning from financial mistakes builds resilience.",
        "Financial success is 80% behavior and habits, and only 20% pure knowledge.",
        "The habit of delaying gratification determines whether you will achieve financial freedom.",
        "Your income grows along with your competence and the value you bring to the market.",
        "Don't buy things you don't need, with money you don't have, to impress people you don't like.",
        "Peace of mind is worth significantly more than owning the latest smartphone model.",
        "Getting rich is a marathon, not a sprint. Direction matters more than momentary speed.",
        "Awareness of life goals gives meaning to saving—you don't save just for numbers, but for your dreams.",
        "Change your financial habits step by step. Small changes yield great long-term results.",
        "Money is a great servant, but a very bad master.",
        "Be tough on financial problems and soft on the people around you.",

        // Advanced & Supplementary Tips
        "Always read contracts before signing them—hidden fees can eat up your entire profit.",
        "Negotiate prices and fees—many financial institutions are willing to lower margins for loyal clients.",
        "Investing in your own personal development and skills increases your earning potential.",
        "Risk management involves planning scenarios like 'what if I lose my job?'.",
        "Don't rely on a single income source; build side hustles.",
        "Children's education expenses should be planned well in advance.",
        "Avoid variable-rate mortgages unless you have a buffer for rising installments.",
        "A clean bank account with no monthly fees is a must—don't overpay for outdated banking services.",
        "Tax optimization is legal and smart—utilize dedicated retirement accounts where available.",
        "Retirement planning starts in your 20s and 30s, not 5 years before you quit working.",
        "Long-term stability requires patience and avoiding chaotic changes to your portfolio.",
        "Profits are realized when you sell, not on paper—panic harms your overall returns.",
        "Analyze your financial mistakes and keep an investment journal.",
        "Buy real estate to live in, not as a guaranteed life investment—real estate markets carry risks too.",
        "Plan vacation and leisure expenses in your budget in advance to avoid tapping into savings.",
        "Take care of your will and financial documentation organization for the sake of your loved ones.",
        "Money makes life easier, but it doesn't buy happiness—it's the foundation upon which you build the rest.",
        "The consistency of small steps can yield unbelievable results after 10 years.",
        "Be forgiving of yourself if you have a month with a worse balance—the yearly trend matters most.",
        "Saving on household utility bills (energy, water) provides a free cash injection every month.",
        "Always have a backup plan in case of serious illness or loss of working capacity.",
        "Avoid advice from people who haven't achieved what they talk about—listen to practitioners in finance.",
        "Financial transparency in a relationship is key to avoiding money conflicts.",
        "Building wealth requires giving up instant gratification for future benefits.",
        "Every dollar spent is a vote for the lifestyle you want to lead.",
        "Do annual financial reviews—check how much your net worth (assets minus liabilities) has grown.",
        "When choosing brokerage accounts, pay attention to safety and regulations.",
        "Don't fall for market hypes on 'hot stocks' or unsupported cryptocurrencies.",
        "Geographic portfolio diversification protects against single-country risks.",
        "Easy-come money easily goes—respect every bit of capital you handle.",
        "Ethics in business and finance pays off in the long run.",
        "Secure your passwords and online banking access—cybersecurity is part of financial hygiene.",
        "Don't be afraid to talk about money with children—financial education at home pays off for a lifetime.",
        "The time value of money is fundamental—$1000 today is worth more than $1000 a year from now.",
        "Managing financial liquidity lets you sleep peacefully without payment deadline worries.",
        "Your ultimate financial goal is not to impress others, but to achieve full independence.",
        "Good dietary habits and home cooking can save you a fortune annually.",
        "Always keep a financial plan mapped out for the next 12 months.",
        "True financial success is the ability to enjoy life while keeping your budget under control.",
        "Invest in assets that generate cash flow (dividends, interest, rent) rather than speculative ones.",
        "Patience in finance is rewarded with manifold higher returns.",
        "Make sure your savings work for you 24 hours a day.",
        "There are no shortcuts to wealth—quick profit promises are almost always scams.",
        "Everything you need to know about financial hygiene fits in simple rules: live below your means, save wisely, and invest for the long term."
    ];

    function getDayOfYear(date) {
        const start = new Date(date.getFullYear(), 0, 0);
        const diff = (date - start) + ((start.getTimezoneOffset() - date.getTimezoneOffset()) * 60 * 1000);
        const oneDay = 1000 * 60 * 60 * 24;
        return Math.floor(diff / oneDay);
    }

    const today = new Date();
    const dayOfYear = getDayOfYear(today);
    const tipIndex = dayOfYear % tips.length;

    const tipElement = document.getElementById("daily-tip");
    if (tipElement) {
        tipElement.textContent = `"${tips[tipIndex]}"`;
    }
});
