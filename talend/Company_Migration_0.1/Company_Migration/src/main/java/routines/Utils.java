package routines;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class Utils {

	/**
	 * {Category} User Defined
	 * 
	 * @param stringToCheck
	 * @return boolean
	 */
	public static boolean stringIsEmpty(String stringToCheck) {
		return stringToCheck.length() == 0;
	}

	/**
	 * {Category} User Defined
	 * 
	 * @param state
	 * @return
	 */
	public static Integer getReceptionPackageState(String state) {
		switch (state) {
		case "quarantine":
			return 1;
		case "litige":
			return 2;
		case "invalid":
			return 3;
		case "ok":
			return 4;
		default:
			return null;
		}
	}

	/**
	 * {Category} User Defined
	 * 
	 * @param type
	 * @return
	 */
	public static Integer getType(String type) {
		try {
			if (type.toLowerCase().length() != 0) {
				switch (type) {
				case "carton":
					return 1;
				case "palette":
					return 2;
				case "unite":
					return 3;
				default:
					return 0;
				}
			} else {
				return null;
			}
		} catch (Exception e) {
			return null;
		}
	}

	/**
	 * {Category} User Defined
	 * 
	 * @param date
	 * @return
	 */
	public static Date getDate(Date date) {
		try {
			SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
			String output = dateFormat.format(date).toString();
			if (output.equals("0002-12-31"))
				return null;
			else
				return date;
		} catch (Exception e) {
			return null;
		}
	}

	/**
	 * 
	 * @param input
	 * @return
	 */
	public static boolean isValidDate(String input) {
		boolean valid = false;
		try {
			SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
			String output = dateFormat.parse(input).toString();
			valid = input.equals(output);
		} catch (Exception ignore) {
		}

		return valid;
	}

	/**
	 * {Category} User Defined
	 * 
	 * @param status
	 * @return
	 */
	public static Integer getReceptionStatus(String status) {
		switch (status) {
		case "recu":
			return 2;
		case "transit":
			return 1;
		default:
			return null;
		}
	}

	/**
	 * {Category} User Defined
	 * 
	 * @param destination
	 * @return
	 */
	public static Integer getDeliveryDestination(String destination) {
		System.out.println(destination);
		if (destination != null) {
			if (destination.equals("export")) {
				return 1;
			} else if (destination.equals("import")) {
				return 2;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	/**
	 * {Category} User Defined
	 * 
	 * @param ht
	 * @return
	 */
	public static Integer getDeliveryOutsideWorkingHours(boolean ht) {
		return (ht) ? 1 : 0;
	}

	/**
	 * {Category} User Defined
	 * 
	 * @param final_destination
	 * @return
	 */
	public static String getFinalDestination(String final_destination) {
		if (final_destination != null) {
			if (final_destination.length() == 0
					&& final_destination.trim().equals("")) {
				return null;
			} else {
				return final_destination;
			}
		} else {
			return null;
		}
	}

	/**
	 * {Category} User Defined
	 * 
	 * @param final_destination
	 * @return
	 */
	public static String getPo(String po) {
		if (po != null) {
			if (po.length() == 0 && po.trim().equals("")) {
				return null;
			} else {
				return po;
			}
		} else {
			return null;
		}
	}
	
}
