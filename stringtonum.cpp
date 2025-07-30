#include <iostream>
#include <string>
#include <vector>
#include <sstream>
#include <unordered_map>
#include <numeric>

// A helper function to split the input string into words
std::vector<std::string> split(const std::string &s)
{
    std::vector<std::string> tokens;
    std::string token;
    std::istringstream tokenStream(s);
    while (tokenStream >> token)
    {
        tokens.push_back(token);
    }
    return tokens;
}

long long wordToNumber(const std::string &s)
{
    // Using const for maps that are not modified
    const std::unordered_map<std::string, long long> numbers = {
        {"zero", 0}, {"one", 1}, {"two", 2}, {"three", 3}, {"four", 4}, {"five", 5}, {"six", 6}, {"seven", 7}, {"eight", 8}, {"nine", 9}, {"ten", 10}, {"eleven", 11}, {"twelve", 12}, {"thirteen", 13}, {"fourteen", 14}, {"fifteen", 15}, {"sixteen", 16}, {"seventeen", 17}, {"eighteen", 18}, {"nineteen", 19}, {"twenty", 20}, {"thirty", 30}, {"forty", 40}, {"fifty", 50}, {"sixty", 60}, {"seventy", 70}, {"eighty", 80}, {"ninety", 90}};

    const std::unordered_map<std::string, long long> scales = {
        {"hundred", 100},
        {"thousand", 1000},
        {"million", 1000000},
        {"billion", 1000000000} // Added for more range
    };

    if (s.empty())
    {
        return 0; // Handle empty input
    }

    auto words = split(s);

    long long result = 0;
    long long current_chunk = 0;

    for (const auto &word : words)
    {
        if (numbers.count(word))
        {
            current_chunk += numbers.at(word);
        }
        else if (word == "hundred")
        {
            // Multiply the current chunk by 100. Handles cases like "three hundred".
            // If current_chunk is 0, it means we saw "hundred" alone, which is fine (0 * 100 = 0).
            current_chunk *= 100;
        }
        else if (scales.count(word))
        {
            // When a scale word is found, process the current chunk.
            // Example: "three hundred thousand" -> current_chunk is 300, word is "thousand"
            current_chunk *= scales.at(word);
            result += current_chunk;
            current_chunk = 0; // Reset for the next chunk
        }
        else if (word == "and")
        {
            // "and" is grammatical fluff, so we just skip it
            continue;
        }
        else
        {
            std::cerr << "Error: Unknown word '" << word << "'" << std::endl;
            return -1; // Return -1 to indicate an error
        }
    }

    // Add any remaining part to the result.
    // This handles the last part of the number, e.g., the "forty two" in "one thousand forty two".
    result += current_chunk;

    return result;
}

int main()
{
    std::string input;

    // A loop to allow for multiple conversions
    while (true)
    {
        std::cout << "Enter a number in words (e.g., 'two million three hundred thousand fifty') or type 'exit' to quit:" << std::endl;
        std::getline(std::cin, input);

        if (input == "exit")
        {
            break;
        }

        long long number = wordToNumber(input);

        if (number != -1)
        {
            std::cout << "✅ The number is: " << number << std::endl;
        }
        else
        {
            std::cout << "❌ Could not parse the input string." << std::endl;
        }
        std::cout << "------------------------------------------" << std::endl;
    }
    return 0;
}