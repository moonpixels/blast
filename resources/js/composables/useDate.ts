import dayjs from 'dayjs'
import localizedFormat from 'dayjs/plugin/localizedFormat'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(localizedFormat)
dayjs.extend(utc)
dayjs.extend(timezone)

export default function useDate(date: string, guessTz = true, format = 'lll'): string {
  if (guessTz) {
    dayjs.tz.guess()
  }

  return dayjs(date).format(format)
}
